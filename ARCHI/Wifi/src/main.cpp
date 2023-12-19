#include <Arduino.h>
#include <WiFi.h> //Wifi library
#include <WiFiClient.h>
#include <WiFiAP.h>

void printLocalTime();
#include "time.h"
const char* ssid = "partageDeCo";
const char* password = "gabin000";
const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;
void setup(){
    Serial.begin(9600);
    // Connect to Wi-Fi
    Serial.print("Connecting to ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected.");
    // Init and get the time
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    printLocalTime();
    //disconnect WiFi as it's no longer needed
    WiFi.disconnect(true);
    WiFi.mode(WIFI_OFF);
}
void loop(){
    delay(1000);
    printLocalTime();
}
void printLocalTime(){
    struct tm timeinfo;
    if(!getLocalTime(&timeinfo)){
        Serial.println("Failed to obtain time");
        return;
    }
    Serial.println(&timeinfo, "%Y-%m-%d %H:%M:%S");
    Serial.print("Month: ");
    Serial.println(&timeinfo, "%B");
    Serial.print("Day of Month: ");
    Serial.println(&timeinfo, "%d");
    Serial.print("Year: ");
    Serial.println(&timeinfo, "%Y");
    Serial.print("Hour: ");
    Serial.println(&timeinfo, "%H");
    Serial.print("Minute: ");
    Serial.println(&timeinfo, "%M");
    Serial.print("Second: ");
    Serial.println(&timeinfo, "%S");

    // Récup l'heure
    char timeHour[3];
    strftime(timeHour,3, "%H", &timeinfo);
    Serial.println(timeHour);
    //
    Serial.println();
}

/*
--------------------------
Connexion à Eduroam
--------------------------
#include "esp_wpa2.h" //wpa2 library for connections to Enterprise networks
#define EAP_IDENTITY "gjoyet" //if connecting from another corporation, use identity@organisation.domain in Eduroam
#define EAP_USERNAME "gjoyet" //oftentimes just a repeat of the identity
#define EAP_PASSWORD "Ztitbw(002)googlegab" //your Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID
const char* host = "arduino.php5.sk"; //external server domain for HTTP connection after authentification
int counter = 0;

// NOTE: For some systems, various certification keys are required to connect to the wifi system.
// Usually you are provided these by the IT department of your organization when certs are required
// and you can't connect with just an identity and password.
// Most eduroam setups we have seen do not require this level of authentication, but you should contact
// your IT department to verify.
// You should uncomment these and populate with the contents of the files if this is required for your scenario (See Example 2 and Example 3 below).
//const char *ca_pem = "insert your CA cert from your .pem file here";
//const char *client_cert = "insert your client cert from your .crt file here";
//const char *client_key = "insert your client key from your .key file here";

void setup() {
    Serial.begin(9600);
    delay(10);
    Serial.println();
    Serial.print("Connecting to network: ");
    Serial.println(ssid);
    WiFi.disconnect(true); //disconnect form wifi to set new wifi connection
    WiFi.mode(WIFI_STA); //init wifi mode
    // Example1 (most common): a cert-file-free eduroam with PEAP (or TTLS)
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD);
    // Example 2: a cert-file WPA2 Enterprise with PEAP
    //WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD, ca_pem, client_cert, client_key);
    // Example 3: TLS with cert-files and no password
    //WiFi.begin(ssid, WPA2_AUTH_TLS, EAP_IDENTITY, NULL, NULL, ca_pem, client_cert, client_key);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
        counter++;
        if(counter>=60){ //after 30 seconds timeout - reset board
            ESP.restart();
        }
    }
    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address set: ");
    Serial.println(WiFi.localIP()); //print LAN IP
}

void loop() {
    if (WiFi.status() == WL_CONNECTED) { //if we are connected to Eduroam network
        counter = 0; //reset counter
        Serial.println("Wifi is still connected with IP: ");
        Serial.println(WiFi.localIP()); //inform user about his IP address
    }
    else if (WiFi.status() != WL_CONNECTED) { //if we lost connection, retry
        WiFi.begin(ssid);
    }
    while (WiFi.status() != WL_CONNECTED) { //during lost connection, print dots
        delay(500);
        Serial.print(".");
        counter++;
        if(counter>=60){ //30 seconds timeout - reset board
            ESP.restart();
        }
    }
    Serial.print("Connecting to website: ");
    Serial.println(host);
    WiFiClient client;
    if (client.connect(host, 80)) {
        String url = "/rele/rele1.txt";
        client.print(String("GET ") + url + " HTTP/1.1\r\n" + "Host: " + host + "\r\n" + "User-Agent: ESP32\r\n" + "Connection: close\r\n\r\n");
        while (client.connected()) {
            String line = client.readStringUntil('\n');
            if (line == "\r") {
                break;
            }
        }
        String line = client.readStringUntil('\n');
        Serial.println(line);
    }
    else{
        Serial.println("Connection unsucessful");
    }
}*/



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