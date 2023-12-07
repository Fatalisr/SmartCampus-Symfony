#include <Arduino.h>

#include "sensirion_common.h"
#include "sgp30.h"
/*
 * Déclaration des fonctions
*/

/*
 * Initializes the sensor
 * Reset all baseline
 * The initialization takes up to around 15 seconds, during which all APIs measuring IAQ (Indoor air quality ) output will not change.
 * Default value is 400(ppm) for co2,0(ppb) for tvoc
 */
void init_CO2_sensor();

/*
 * @brief Read the value from the sensor
 * @param ppm The var where the C02 values will be stored
 * @return the error. STATUS_OK if success, STATUS_FAIL in the other case
*/
s16 get_CO2_value(u16 &ppm);

/*
 * @brief Setup of the sensor
*/
void setup() {
  Serial.begin(9600); // Arduino va échanger des messages avec le moniteur série, à un débit de données de 9600 bits par seconde.
  init_CO2_sensor();  // Initialisation du capteur
  Serial.println("serial start!!");
}

/*
 * @brief Loop where the sensor will be stuck in
*/
void loop()
{
  s16 err_CO2;
  u16 ppm;
  err_CO2 = get_CO2_value(ppm);
  if(err_CO2 == STATUS_OK)
  {
    Serial.print("CO2 : ");
    Serial.print(ppm);
    Serial.println("ppm");
  }
  else
  {
    Serial.println("Erreur CO2");
  }
  delay(5000);
}

/*
 * Définition des fonctions
*/
void init_CO2_sensor()
{
  s16 err;
  u16 scaled_ethanol_signal, scaled_h2_signal;
  while (sgp_probe() != STATUS_OK)
  {
    Serial.println("Erreur de l'initialisation du Capteur C02");
    while(1);
  }
  /* Read H2 and Ethanol signal in the way of blocking */
  err = sgp_measure_signals_blocking_read(&scaled_ethanol_signal,&scaled_h2_signal);
  if (err == STATUS_OK)
  {
    Serial.println("get ram signal!");
  }
  else
  {
    Serial.println("Erreur de lecture"); 
  }
  err = sgp_iaq_init();
}

s16 get_CO2_value(u16 &ppm)
{
  s16 err = 0;
  err = sgp_measure_co2_eq_blocking_read(&ppm);
  return err;
}