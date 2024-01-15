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
/*                              WIFI                               */
/*-----------------------------------------------------------------*/
#define EAP_IDENTITY "ugay" //Login
#define EAP_USERNAME "ugay" //Login
#define EAP_PASSWORD "LaceulalTelephone7!" //Eduroam password
extern const char* ssid; 

/*-----------------------------------------------------------------*/
/*                              LED                                */
/*-----------------------------------------------------------------*/
extern bool ledTempOk;
extern bool ledHumiOk;
extern bool ledCO2Ok;

/*-----------------------------------------------------------------*/
/*                        Capteur de CO2                           */
/*-----------------------------------------------------------------*/
extern u16 ppm;
extern s16 err_CO2;

/*-----------------------------------------------------------------*/
/*               Capteur de temperature/humidité                   */
/*-----------------------------------------------------------------*/
extern float temperature;
extern float humidity;
#define DHTPIN 17// Pin du capteur de temperature/humidité

/*-----------------------------------------------------------------*/
/*                              API                                */
/*-----------------------------------------------------------------*/
extern int APIDelay;
extern String ESPCurrentRoom;

/*-----------------------------------------------------------------*/
/*                              TASK                               */
/*-----------------------------------------------------------------*/
extern int ledTaskPriority;
extern int screenTaskPriority;
extern int CO2TaskPriority;
extern int TempHumiTaskPriority;
extern int APITaskPriority;