#include <Arduino.h>
#include <WiFi.h> //Wifi library
#include <HTTPClient.h>
#include <ArduinoJson.h>

#include "esp_wpa2.h" //wpa2 library for connections to Enterprise networks
#define EAP_IDENTITY "gjoyet" //if connecting from another corporation, use identity@organisation.domain in Eduroam
#define EAP_USERNAME "gjoyet" //oftentimes just a repeat of the identity
#define EAP_PASSWORD "Ztitbw(002)googlegab" //your Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID


//Your Domain name with URL path or IP address with path
String serverName = "https://sae34.k8s.iut-larochelle.fr/api";


// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)
//unsigned long timerDelay = 600000;
// Set timer to 5 seconds (5000)
unsigned long timerDelay = 5000;
String sensorReadings;
float sensorReadingsArr[3];

void setup()
{
    Serial.begin(9600);
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD);
    Serial.println("Connecting");
    while(WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.print("Connected to WiFi network with IP Address: ");
    Serial.println(WiFi.localIP());
    Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");
}
void loop()
{
    //Send an HTTP POST request every 10 minutes
    if ((millis() - lastTime) > timerDelay)
    {
        //Check WiFi connection status
        if(WiFi.status()== WL_CONNECTED)
        {

            StaticJsonDocument<200> file;
            file["nom"] = "temp";
            file["valeur"] = "19,2";
            file["dateCapture"] = "2023-12-20 08:00:00";
            file["localisation"] = "D004";
            file["description"] = "";
            file["nomsa"] = "13";
            serializeJson(file, Serial);
            Serial.println("");



            /*sensorReadings = httpGETRequest(serverName);
            JSONVar myObject = JSON.parse(sensorReadings);
            // JSON.typeof(jsonVar) can be used to get the type of the var
            if (JSON.typeof(myObject) == "undefined")
            {
                Serial.println("Parsing input failed!");
                return;
            }
            Serial.print("JSON object = ");
            Serial.println(myObject);
            // myObject.keys() can be used to get an array of all the keys in the object
            JSONVar keys = myObject.keys();
            for (int i = 0; i < keys.length(); i++)
            {
                JSO value = myObject[keys[i]];
                Serial.print(keys[i]);
                Serial.print(" = ");
                Serial.println(value);
                sensorReadingsArr[i] = double(value);
            }
            Serial.print("1 = ");
            Serial.println(sensorReadingsArr[0]);
            Serial.print("2 = ");
            Serial.println(sensorReadingsArr[1]);
            Serial.print("3 = ");
            Serial.println(sensorReadingsArr[2]);*/
        }
        else
        {
            Serial.println("WiFi Disconnected");
        }
        lastTime = millis();
        }
}

/*
--------------------------
Connexion à Eduroam
--------------------------
*/



/* 
---------------------
Connexion à un wifi
---------------------
const char* ssid = "partageDeCo";
const char* password = "gabin000";
void setup()
{
    Serial.begin(115200);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.print("Adresse IP : ");
    Serial.println(WiFi.localIP());
}
void loop()
{

}
*/
/*
----------------------
Scan des réseaux Wifi
----------------------
void setup()
{
    Serial.begin(9600);
    // Set WiFi to station mode and disconnect from an AP if it was previously connected
    WiFi.mode(WIFI_STA);
    WiFi.disconnect();
    delay(100);
    Serial.println("Setup done");
}
void loop()
{
    Serial.println("scan start");
    // WiFi.scanNetworks will return the number of networks found
    int n = WiFi.scanNetworks();
    Serial.println("scan done");
    if (n == 0) {
        Serial.println("no networks found");
    } else {
        Serial.print(n);
        Serial.println(" networks found");
    for (int i = 0; i < n; ++i) {
        // Print SSID and RSSI for each network found
        Serial.print(i + 1);
        Serial.print(": ");
        Serial.print(WiFi.SSID(i));
        Serial.print(" (");
        Serial.print(WiFi.RSSI(i));
        Serial.print(")");
        Serial.println((WiFi.encryptionType(i) == WIFI_AUTH_OPEN)?" ":"*");
        delay(10);
    }
    }
    Serial.println("");
    // Wait a bit before scanning again
    delay(5000);
}
*/