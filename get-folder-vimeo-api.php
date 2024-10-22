<?php 

function getVimeoFolderVideos($folderId) {
    // URL dell'API Vimeo per ottenere i video di una cartella specifica
    $apiUrl = "https://api.vimeo.com/users/user_id/projects/$folderId/videos";

    // Inizializzazione cURL
    $ch = curl_init();

    // Configurazione di cURL
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer token"
    ]);

    // Esecuzione della richiesta
    $response = curl_exec($ch);

    // Verifica di errori
    if (curl_errno($ch)) {
        return 'Errore cURL: ' . curl_error($ch);
    }

    // Chiusura della connessione cURL
    curl_close($ch);

    // Decodifica della risposta JSON
    $videos = json_decode($response, true);

    // Verifica che la risposta contenga i dati
    if (!isset($videos['data'])) {
        return 'Nessun video trovato o errore nella risposta.';
    }

    // Array che conterrà le informazioni sui video
    $result = [];

    // Itera attraverso i video nella cartella
    foreach ($videos['data'] as $video) {
        // Estrai le informazioni necessarie
        $videoId = $video['uri'];
        $videoTitle = $video['name'];
        $thumbnailUrl = $video['pictures']['sizes'][0]['link'];
        $embedHtml = $video['embed']['html'];
        $embedUrl = $video['player_embed_url'];

        // Aggiungi i dettagli del video all'array di risultato
        $result[] = [
            'id' => $videoId,
            'title' => $videoTitle,
            'thumbnail' => $thumbnailUrl,
            'embed_html' => $embedHtml,
            'embed_url' => $embedUrl
        ];
    }

    // Restituisci l'array con le informazioni sui video
    return $result;
}

?>
