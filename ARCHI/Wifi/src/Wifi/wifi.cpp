/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include "wifi.h"

/*-----------------------------------------------------------------*/
/*                          Variables                              */
/*-----------------------------------------------------------------*/

int counter;//Compteur pour la connexion

/*-----------------------------------------------------------------*/
/*                          Fonction                               */
/*-----------------------------------------------------------------*/

void connectedToWifi(){
    // Initialisation de la connexion avec les variables adequats
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD); 
    Serial.println("Tentative de Connexion");

    // Gestion du temps de connexion
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
