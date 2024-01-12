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
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

void sendToApi();

/*
* @brief create the json object
*/
template <typename t>
String convertTOJson(t value, String type);

/*
 * Initializes the clock
 * 
 */
void initClock();

/*
* @brief get a char * reprsenting the date
*/
char * getDate();

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

void sendToAPITask(void *parameter);