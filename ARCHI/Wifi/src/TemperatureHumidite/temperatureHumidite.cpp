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

#include "temperatureHumidite.h"

#define DHTPIN 17// Digital pin connected to the DHT sensor 
#define DHTTYPE    DHT22// DHT 22 (AM2302)
DHT_Unified dht(DHTPIN, DHTTYPE);// Instance du capteur de Hum/Temp

float temperature;
float humidity;

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

// Initiation du capteur
void initTempHumSensor()
{
    // Initialize temperature sensor
    dht.begin();
    Serial.println("Init Hum/Temp OK");
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

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

// Capture les valeurs et incremente les variables globales correspondante
void getHumTempTask(void *parameter){
    for(;;){
        getHumTempvalue(humidity,temperature);
        vTaskDelay(pdMS_TO_TICKS( 10000 )); 
    }
}