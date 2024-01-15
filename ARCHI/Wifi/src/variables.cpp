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

#include "variables.h"

/*
* Fichier contenant toutes les variables globales utile au parametrage du SA
*/

/*-----------------------------------------------------------------*/
/*                              LED                                */
/*-----------------------------------------------------------------*/

/*
* Booleen permettant de gerer la couleur de la led
* Orange = initialisation de l'esp
* Vert = DATA OK
* Rouge = Probleme
*/ 
bool ledTempOk = true;
bool ledHumiOk = true;
bool ledCO2Ok = true;


/*-----------------------------------------------------------------*/
/*                        Capteur de CO2                           */
/*-----------------------------------------------------------------*/
u16 ppm;
s16 err_CO2;

/*-----------------------------------------------------------------*/
/*               Capteur de temperature/humidité                   */
/*-----------------------------------------------------------------*/
float temperature;
float humidity;

/*-----------------------------------------------------------------*/
/*                              API                                */
/*-----------------------------------------------------------------*/
int APIDelay = 300000; // Temps d'attente entre chaque envoie a l'API
String ESPCurrentRoom = "D004"; // Salle de notre SA

/*-----------------------------------------------------------------*/
/*                              WIFI                               */
/*-----------------------------------------------------------------*/
const char* ssid = "eduroam"; // Eduroam SSID

/*-----------------------------------------------------------------*/
/*                      PRIORITÉ DES TASKS                         */
/*-----------------------------------------------------------------*/
int ledTaskPriority = 1;
int screenTaskPriority = 1;
int CO2TaskPriority = 3;
int TempHumiTaskPriority = 3;
int APITaskPriority = 5;