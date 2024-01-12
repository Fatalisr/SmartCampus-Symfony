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

#include "TemperatureHumidite/temperatureHumidite.h"
#include "Co2/co2.h"
#include "API/gestion_api.h"
#include "Wifi/wifi.h"

/*-----------------------------------------------------------------*/
/*                         Setup et loop                           */
/*-----------------------------------------------------------------*/


void setup()
{
    Serial.begin(9600);

    connectedToWifi();

    initClock();
    initCO2Sensor();
    initTempHumSensor();
    Serial.println("End of Setup");

    int status = WL_IDLE_STATUS;

    /*-----------------------------------------------------------------*/
    /*                               TASKS                             */
    /*-----------------------------------------------------------------*/
    xTaskCreate(getCO2Task, "capture du CO2", 10000, NULL, 2, NULL);
    xTaskCreate(getHumTempTask, "capture temp et humi", 10000, NULL, 2, NULL);
    xTaskCreate(sendToAPITask, "envoie à l'api", 10000, NULL, 1, NULL);

}

void loop()
{}





