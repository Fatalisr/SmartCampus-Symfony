/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include "co2.h"

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

// Fonction d'initialisation du capteur
void initCO2Sensor()
{
  s16 err;
  u16 scaled_ethanol_signal, scaled_h2_signal;
  while (sgp_probe() != STATUS_OK)
  {
    Serial.println("Erreur de l'initialisation du Capteur C02");
    ledCO2Ok = false; 
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
    Serial.println("Erreur de lecture du capteur de CO2");
    ledCO2Ok = false; 
  }
  err = sgp_iaq_init();
  Serial.println("Init CO2 OK");
  ledCO2Ok = true;
}

// Fonction de lecture du capteur de CO2
s16 getCO2Value(u16 &ppm)
{
  s16 err = 0;
  u16 scaled_ethanol_signal, scaled_h2_signal;

  if (sgp_measure_signals_blocking_read(&scaled_ethanol_signal,&scaled_h2_signal) == STATUS_OK){
    ledCO2Ok = true; 
  }
  else{
    ledCO2Ok = false; 
  }

  err = sgp_measure_co2_eq_blocking_read(&ppm);
  return err;
}

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

// Tache de lecture du CO2
void getCO2Task(void *parameter){
    for(;;){
        err_CO2 = getCO2Value(ppm);
        vTaskDelay( pdMS_TO_TICKS( 2000 ) );
    }
}