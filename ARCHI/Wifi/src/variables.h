#ifndef VARIABLE_H
#define VARIABLE_H
/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include <Arduino.h>
#include "sensirion_common.h"
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
#include "LED/led.h"
#include "Ecran/ecran.h"

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
#define DHTPIN 7// Pin du capteur de temperature/humidité

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

#endif
