#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP32Time.h>
#include "sensirion_common.h"
#include "sgp30.h"
#include "esp_wpa2.h" //Librairie wpa2 pour la connexion au réseaux d'enterprise
#include <Adafruit_Sensor.h>
#include <DHT_U.h>

#define DHTPIN 17     // Digital pin connected to the DHT sensor 

#define DHTTYPE    DHT22     // DHT 22 (AM2302)

// Instance du capteur
DHT_Unified dht(DHTPIN, DHTTYPE);

#define EAP_IDENTITY "ugay" //Login
#define EAP_USERNAME "ugay" //Login
#define EAP_PASSWORD "LaceulalTelephone7!" //Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID
const char* ntpServer = "pool.ntp.org";

const String serverName = "https://sae34.k8s.iut-larochelle.fr/api/captures"; //URL de l'api pour l'envoie de données
int counter;//Compteur pour la connexion
unsigned long lastTime = 0;//Compteur pour le délai entre chaque envoie
unsigned long timerDelay = 30000;//Délai de 30 secondes
//ESP32Time rtc;
ESP32Time rtc(3600);  // GMT+1
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;

float temperature;
float humidity;
u16 ppm;
s16 err_CO2;


/*
* @brief create the json object
*/
template <typename t>
String convertTOJson(t value, String type);

/*
* @brief get a char * reprsenting the date
*/
char * getDate();
void getHumTempvalue(float & hum, float & temp);

/*
 * @brief Read the value from the sensor
 * @param ppm The var where the C02 values will be stored
 * @return the error. STATUS_OK if success, STATUS_FAIL in the other case
*/
s16 getCO2Value(u16 &ppm);

/*
 * Initializes the clock
 * 
 */
void initClock();

/*
 * Initializes the sensor
 * Reset all baseline
 * The initialization takes up to around 15 seconds, during which all APIs measuring IAQ (Indoor air quality ) output will not change.
 * Default value is 400(ppm) for co2,0(ppb) for tvoc
 */
void initCO2Sensor();

void initTempHumSensor();

void sendToApi();

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
    initClock();
    initCO2Sensor();
    initTempHumSensor();
    Serial.println("End of Setup");

    int status = WL_IDLE_STATUS;
}
void loop()
{
    sendToApi();
}

/*-----------------------------------------------------------------*/
/*               FONCTIONS RELATIVES AUX CAPTEURS                  */
/*-----------------------------------------------------------------*/

template <typename t>
String convertTOJson(t value,String type)
{
    //Envoyer la valeur puis un string qui dit le type de la donnée. 
    //Ex : "temp" ,"hum" ou "co2"
    //Renvoie le json pour la requete http post
    StaticJsonDocument<200> file;
    String content;
    String date = getDate();
    file["nom"] = type;
    file["valeur"] = value;
    file["dateCapture"] = date;
    file["localisation"] = "D004";
    file["description"] = "";
    file["nomsa"] = "13";
    serializeJson(file, content);
    return content;
}

char * getDate()
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

// Fonction de lecture du capteur de CO2
s16 getCO2Value(u16 &ppm)
{
  s16 err = 0;
  err = sgp_measure_co2_eq_blocking_read(&ppm);
  return err;
}
// fonction de capture de l'humidité et de la temp
void getHumTempvalue(float & hum, float & temp){
    sensors_event_t event;

    // Get temperature event and print its value.
    dht.temperature().getEvent(&event); 
    if (isnan(event.temperature)) {
    Serial.println(F("Error reading temperature!"));
    }
    else {
        temperature = event.temperature;
    }

    // Get humidity event and print its value.
        dht.humidity().getEvent(&event);
    if (isnan(event.relative_humidity)) {
        Serial.println(F("Error reading humidity!"));
    }
    else {
        humidity = event.relative_humidity;
    }
}

// Fonction d'initialisation du capteur
void initCO2Sensor()
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
  Serial.println("Init CO2 OK");
}
void initTempHumSensor()
{
    // Initialize temperature sensor
    dht.begin();
  Serial.println("Init Hum/Temp OK");
}
void initClock()
{
    /*---------set with NTP---------------*/
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    struct tm timeinfo;
    if (getLocalTime(&timeinfo))
    {
        rtc.setTimeStruct(timeinfo); 
    }
}

void sendToApi()
{
    HTTPClient https;
    getCO2Value(ppm);
    getHumTempvalue(humidity,temperature);
    String SvalueCO2 = String(ppm,DEC);
    String SvalueHum = String(humidity,DEC);
    SvalueHum = SvalueHum.substring(0,4);
    String SvalueTemp = String(temperature,DEC);
    SvalueTemp = SvalueTemp.substring(0,4);
    String contentCO2 = convertTOJson(SvalueCO2,"co2");
    String contentHum = convertTOJson(SvalueHum,"hum");
    String contentTemp = convertTOJson(SvalueTemp,"temp");
    String contentType = "application/json";
    String postDataCO2 = contentCO2;
    String postDataHum = contentHum;
    String postDataTemp = contentTemp;
    String dbname = "sae34bdm1eq1";
    String username = "m1eq1";
    String userpass = "sodqif-vefXym-0cikho";
    //Send an HTTPS POST request every 30 seconds
    if ((millis() - lastTime) > timerDelay)
    {
        //Check WiFi connection status
        if(WiFi.status()== WL_CONNECTED)
        {
            Serial.println("Sending POST request CO2");
            https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
            https.addHeader("accept",contentType);
            https.addHeader("dbname",dbname);
            https.addHeader("username",username);
            https.addHeader("userpass",userpass);
            https.addHeader("Content-Type", contentType);
            int httpResponseCode1 = https.POST(postDataCO2);
            Serial.println(https.errorToString(httpResponseCode1));
            //
            Serial.println("Sending POST request Hum");
            https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
            https.addHeader("accept",contentType);
            https.addHeader("dbname",dbname);
            https.addHeader("username",username);
            https.addHeader("userpass",userpass);
            https.addHeader("Content-Type", contentType);
            int httpResponseCode2 = https.POST(postDataHum);
            Serial.println(https.errorToString(httpResponseCode2));
            //
            Serial.println("Sending POST request Temp");
            https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
            https.addHeader("accept",contentType);
            https.addHeader("dbname",dbname);
            https.addHeader("username",username);
            https.addHeader("userpass",userpass);
            https.addHeader("Content-Type", contentType);
            int httpResponseCode3 = https.POST(postDataTemp);
            Serial.println(https.errorToString(httpResponseCode3));
        }
        else
        {
            Serial.println("WiFi Disconnected");
        }
        lastTime = millis();
    }
    delay(1000);
}

/*-----------------------------------------------------------------*/
/*                               TASKS                             */
/*-----------------------------------------------------------------*/

// Tache de lecture du CO2
void getCO2Task(void *parameter){
    for(;;){
        err_CO2 = getCO2Value(ppm);
        vTaskDelay( pdMS_TO_TICKS( 15000 ) );
    }
}

// Capture les valeurs et incremente les variables globales correspondante
void getHumTempTask(void *parameter){
    for(;;){
        getHumTempvalue(humidity,temperature);
        vTaskDelay(pdMS_TO_TICKS( 10000 )); 
    }
}


