<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
use App\Models\Person; 
use App\Export\UtentiAggr;
use Illuminate\Support\Facades\Cache;
class userage extends Command
{
    private static $contatore = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //indica come sarà richiamato il comando dalla console, tra le parentesi inseriamo il parametro da usare
    protected $signature = 'aggrega:utenti {anni}';

    /**
     * The console command description.
     *  descrizione del comando, che apparirà utilizzando il comando --help
     * @var string
     */

    protected $description = 'Aggrega utenti per età e genera una collection in un file XLS';

    /**
     * Execute the console command.
     * in qeusta funzione definiamo i vari parametri.
     */
    public function handle()
    {
        //self::$contatore ++ ;
        self::$contatore = Cache::increment('contatore', 1);
        //questo è l'argomento che do al comando
        $anni = $this->argument('anni');
        //questo è il risultato che il comando deve restituire, in questo caso tutti gli utenti maggiori di una specifica età
        $utentiAggregati = Person::whereRaw("YEAR(CURRENT_DATE) - YEAR(data_di_nascita) > ?", [$anni])->get();
        //ho indicato che per ricavare l'età deve sottrarre il valore dell'anno corrente, dall'anno di nascità
        //dato che si trova nel modello Person

         // Crea un nome di file univoco basato sul giorno corrente
    $nomeFile = 'utenti_' . now()->format('Ymd') . '.xlsx';

    // Imposta il percorso completo del file locale utilizzando il nuovo nome di file
    //$localFilePath = storage_path('app/' . $nomeFile);

        // Utilizza la classe di esportazione per esportare i dati in un file XLS
        $export = new UtentiAggr($utentiAggregati);
        //Excel::store($export, "utenti2{$contatore}.xlsx"); 
        Excel::store($export, "utenti" . self::$contatore . ".xlsx");
    
        // Visualizza la Collection sulla console
        $this->mostraSuConsole($utentiAggregati);

        
        
    }

        private function mostraSuConsole($utentiAggregati)
    {
        // Utilizza il metodo pluck per estrarre solo il valore della colonna 'data di nascita' utilizzando un array
        // nel caso volessimo prende più colonne
        $datiNascita = $utentiAggregati->pluck('nome');
        // Utilizza il metodo dd (dump and die) per visualizzare la Collection
        dd($datiNascita);
    }
    
    //a questo punto per utilizzare il comando, mi basta lanciarlo da console php artisan aggrega:utenti 20
    //per vedere tutti gli utenti che hanno più di 20 anni
}