/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

// Fichier du projet
#include "temperatureHumidite.h"

/*-----------------------------------------------------------------*/
/*                           Variables                             */
/*-----------------------------------------------------------------*/

#define DHTTYPE DHT22// DHT 22 (AM2302)
DHT_Unified dht(DHTPIN, DHTTYPE);// Instance du capteur de Hum/Temp

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
        ledTempOk = false; 
    }
    else {
        temperature = event.temperature;
        ledTempOk = true; 
    }

    // Get humidity event and print its value.
        dht.humidity().getEvent(&event);
    if (isnan(event.relative_humidity)) {
        Serial.println(F("Error reading humidity!"));
        ledHumiOk = false; 
    }
    else {
        humidity = event.relative_humidity;
        ledHumiOk = true; 
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