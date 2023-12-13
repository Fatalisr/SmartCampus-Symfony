// main.cpp
#include <Arduino.h>

#define digital_pir_sensor 5 // connect to Pin 5 your motion sensor

/*
 * @brief Read the value from the sensor
 * @return 1 if there is a presence detected else return 0.
 */
bool get_Motion_value();

/*
 * @brief Initializes the sensor
 */
void init_motion_sensor();

/*
 * @brief Setup of the sensor
*/
void setup()
{
  Serial.begin(9600);  // set baud rate as 115200
  init_motion_sensor();
}


/*
 * @brief Loop where the sensor will be stuck in
*/
void loop()
{
  bool state = get_Motion_value(); // take if there is a motion
  if (state == 1)
    Serial.println("A Motion has occured");  // When there is a response
  else if (state == 0)
    Serial.println("Nothing Happened");  // Far from PIR sensor
  delay(1000);
}


bool get_Motion_value()
{
  bool state = digitalRead(digital_pir_sensor); // read from PIR sensor
  return state; // return 1 if a motion is detected return 0 else
}

void init_motion_sensor()
{
    pinMode(digital_pir_sensor,INPUT); // set Pin mode as input
}