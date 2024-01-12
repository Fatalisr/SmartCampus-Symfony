/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/
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
#include <U8g2lib.h>
#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif

#include "gestion_api.h"
#include "/home/alex/Documents/UNIV/2023-2024/SAE/2023-2024-but-info-2-a-sae-34-m-1-m-11/ARCHI/Wifi/src/TemperatureHumidite/temperatureHumidite.h"
#include "/home/alex/Documents/UNIV/2023-2024/SAE/2023-2024-but-info-2-a-sae-34-m-1-m-11/ARCHI/Wifi/src/Co2/co2.h"

//ESP32Time rtc;
ESP32Time rtc(3600);  // GMT+1
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;

const char* ntpServer = "pool.ntp.org";

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

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
    file["nomsa"] = "ESP-013";
    serializeJson(file, content);
    return content;
}


void sendToApi()
{
    HTTPClient https;

    String SvalueCO2 = String(ppm,DEC);
    String SvalueHum = String(humidity,DEC);
    SvalueHum = SvalueHum.substring(0,4);
    String SvalueTemp = String(temperature,DEC);
    SvalueTemp = SvalueTemp.substring(0,4);

    String contentCO2 = convertTOJson(SvalueCO2,"co2");
    String contentHum = convertTOJson(SvalueHum,"hum");
    String contentTemp = convertTOJson(SvalueTemp,"temp");

    String contentType = "application/json";
    String dbname = "sae34bdm1eq1";
    String username = "m1eq1";
    String userpass = "sodqif-vefXym-0cikho";


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
        int httpResponseCode1 = https.POST(contentCO2);
        Serial.println(https.errorToString(httpResponseCode1));
        //
        Serial.println("Sending POST request Hum");
        https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
        https.addHeader("accept",contentType);
        https.addHeader("dbname",dbname);
        https.addHeader("username",username);
        https.addHeader("userpass",userpass);
        https.addHeader("Content-Type", contentType);
        int httpResponseCode2 = https.POST(contentHum);
        Serial.println(https.errorToString(httpResponseCode2));
        //
        Serial.println("Sending POST request Temp");
        https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
        https.addHeader("accept",contentType);
        https.addHeader("dbname",dbname);
        https.addHeader("username",username);
        https.addHeader("userpass",userpass);
        https.addHeader("Content-Type", contentType);
        int httpResponseCode3 = https.POST(contentTemp);
        Serial.println(https.errorToString(httpResponseCode3));
    }
   
    delay(1000);
}

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

// Capture les valeurs et incremente les variables globales correspondante
void sendToAPITask(void *parameter){
    vTaskDelay(pdMS_TO_TICKS( 15000 )); 
    for(;;){
        sendToApi();
        vTaskDelay(pdMS_TO_TICKS( 60000 )); 
    }
}