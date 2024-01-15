/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/
// Librairies 
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
#include <WS2812FX.h>

// Fichiers du projet
#include "TemperatureHumidite/temperatureHumidite.h"
#include "Co2/co2.h"
#include "API/gestion_api.h"
#include "Wifi/wifi.h"
#include "LED/led.h"
#include "Ecran/ecran.h"


/*-----------------------------------------------------------------*/
/*                         Setup et loop                           */
/*-----------------------------------------------------------------*/


void setup()
{
    Serial.begin(9600); // Vitesse du moniteur

    // Connection au wifi 
    connectedToWifi();

    // Initialisation des differents composants
    initLed(); 
    initClock();
    initCO2Sensor();
    initTempHumSensor();
    init_screen();

    Serial.println("End of Setup");

    /*-----------------------------------------------------------------*/
    /*                               TASKS                             */
    /*-----------------------------------------------------------------*/
    xTaskCreate(setLedColorTask, "couleur de led", 10000, NULL, ledTaskPriority, NULL);
    xTaskCreate(getCO2Task, "capture du CO2", 10000, NULL,CO2TaskPriority, NULL);
    xTaskCreate(getHumTempTask, "capture temp et humi", 10000, NULL, TempHumiTaskPriority, NULL);
    xTaskCreate(sendToAPITask, "envoie à l'api", 10000, NULL, APITaskPriority, NULL);
    //xTaskCreate(displayValuesOnScreenTask, "affichage a l'écran", 10000,NULL, screenTaskPriority, NULL);
}

void loop()
{
    getCO2Value(ppm);
}





