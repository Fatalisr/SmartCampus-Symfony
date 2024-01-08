#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP32Time.h>
#include "sensirion_common.h"
#include "sgp30.h"
#include "esp_wpa2.h" //Librairie wpa2 pour la connexion au réseaux d'enterprise

#define EAP_IDENTITY "ugay" //Login
#define EAP_USERNAME "ugay" //Login
#define EAP_PASSWORD "LaceulalTelephone7!" //Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID
const char* ntpServer = "pool.ntp.org";

const String serverName = "https://sae34.k8s.iut-larochelle.fr/api/captures"; //URL de l'api pour l'envoie de données
int counter;//Compteur pour la connexion
unsigned long lastTime = 0;//Compteur pour le délai entre chaque envoie
unsigned long timerDelay = 300000;//Délai de 30 secondes
//ESP32Time rtc;
ESP32Time rtc(3600);  // GMT+1
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;



/*
* @brief create the json object
*/
template <typename t>
String convertTOJson(t value, String type);

/*
* @brief get a char * reprsenting the date
*/
char * get_Date();

/*
 * Initializes the clock
 * 
 */
void init_Clock();

/*
 * Initializes the sensor
 * Reset all baseline
 * The initialization takes up to around 15 seconds, during which all APIs measuring IAQ (Indoor air quality ) output will not change.
 * Default value is 400(ppm) for co2,0(ppb) for tvoc
 */
void init_CO2_sensor();

/*
 * @brief Read the value from the sensor
 * @param ppm The var where the C02 values will be stored
 * @return the error. STATUS_OK if success, STATUS_FAIL in the other case
*/
s16 get_CO2_value(u16 &ppm);


void setup()
{
    Serial.begin(9600);
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD);
    Serial.println("Tentative de Connexion");
    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
        counter++;
        if(counter>=60)
        {
            Serial.println("Restart");
            ESP.restart();
        }
    }
    Serial.println("");
    Serial.print("Connected to WiFi with IP : ");
    Serial.println(WiFi.localIP());
    init_Clock();
    init_CO2_sensor();
    Serial.println("End of Setup");

    int status = WL_IDLE_STATUS;
}
void loop()
{
    HTTPClient https;
    u16 value_CO2;
    get_CO2_value(value_CO2);
    String Svalue = String(value_CO2,DEC);
    String content = convertTOJson(Svalue,"co2");
    String contentType = "application/json";
    String postData = content;
    String dbname = "sae34bdm1eq1";
    String username = "m1eq1";
    String userpass = "sodqif-vefXym-0cikho";
    //Send an HTTPS POST request every 30 seconds
    if ((millis() - lastTime) > timerDelay)
    {
        //Check WiFi connection status
        if(WiFi.status()== WL_CONNECTED)
        {
            Serial.println("Sending POST request");
            https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
            https.addHeader("accept",contentType);
            https.addHeader("dbname",dbname);
            https.addHeader("username",username);
            https.addHeader("userpass",userpass);
            https.addHeader("Content-Type", contentType);
            int httpResponseCode = https.POST(postData);
            Serial.println(https.errorToString(httpResponseCode));
        }
        else
        {
            Serial.println("WiFi Disconnected");
        }
        lastTime = millis();
    }
    delay(1000);
}

template <typename t>
String convertTOJson(t value,String type)
{
    //Envoyer la valeur puis un string qui dit le type de la donnée. 
    //Ex : "temp" ,"hum" ou "co2"
    //Renvoie le json pour la requete http post
    StaticJsonDocument<200> file;
    String content;
    String date = get_Date();
    file["nom"] = type;
    file["valeur"] = value;
    file["dateCapture"] = date;
    file["localisation"] = "D004";
    file["description"] = "";
    file["nomsa"] = "13";
    serializeJson(file, content);
    return content;
}

void init_Clock()
{
    /*---------set with NTP---------------*/
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    struct tm timeinfo;
    if (getLocalTime(&timeinfo))
    {
        rtc.setTimeStruct(timeinfo); 
    }
}


char * get_Date()
{
    char * date = (char*)malloc(20);
    snprintf(date, 20,
            PSTR("%04u-%02u-%02u %02u:%02u:%02u"),
            rtc.getYear(),
            rtc.getMonth() + 1,
            rtc.getDay(),
            rtc.getHour(true),
            rtc.getMinute(),
            rtc.getSecond()
            );
  return date;
}
void init_CO2_sensor()
{
  s16 err;
  u16 scaled_ethanol_signal, scaled_h2_signal;
  while (sgp_probe() != STATUS_OK)
  {
    Serial.println("Erreur de l'initialisation du Capteur C02");
    while(1);
  }
  /* Read H2 and Ethanol signal in the way of blocking */
  err = sgp_measure_signals_blocking_read(&scaled_ethanol_signal,&scaled_h2_signal);
  if (err == STATUS_OK)
  {
    Serial.println("get ram signal!");
  }
  else
  {
    Serial.println("Erreur de lecture"); 
  }
  err = sgp_iaq_init();
}

s16 get_CO2_value(u16 &ppm)
{
  s16 err = 0;
  err = sgp_measure_co2_eq_blocking_read(&ppm);
  return err;
}
