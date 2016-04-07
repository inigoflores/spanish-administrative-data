<?

$consulta_provinciero_endpoint = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaProvincia";
$consulta_municipiero_endpoint = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejeroCodigos.asmx/ConsultaMunicipioCodigos";

// CLI Options
$options = 0;

if (in_array('--pretty_print',$argv)) {
    $options|=JSON_PRETTY_PRINT;
}

if (in_array('--unescaped_unicode',$argv)) {
    $options|=JSON_UNESCAPED_UNICODE;
}


// Iteramos las provincias y extraemos los municipios

$allMunicipios = array();
for ($i=1;$i<=52;$i++) {
    if (in_array($i,[1,20,31,48])) { //quitamos provincias del Pais Vasco y Navarra
        continue;
    }
    $xml=simplexml_load_file("{$consulta_municipiero_endpoint}?CodigoProvincia={$i}&CodigoMunicipio=&CodigoMunicipioIne=");
    $json = json_encode($xml->municipiero);
    $municipios = json_decode($json);

    if (!is_array($municipios->muni)) { // Para procesar las provincias con un solo municipio (e.j. Ceuta)
        $municipios->muni=[$municipios->muni];
    }
    $allMunicipios = array_merge($allMunicipios,$municipios->muni);
}

echo json_encode($allMunicipios, $options);



