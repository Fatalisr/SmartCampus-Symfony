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

/*-----------------------------------------------------------------*/
/*                          Variables                              */
/*-----------------------------------------------------------------*/

#define EAP_IDENTITY "ugay" //Login
#define EAP_USERNAME "ugay" //Login
#define EAP_PASSWORD "LaceulalTelephone7!" //Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID
int counter;//Compteur pour la connexion

void connectedToWifi(){
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
}