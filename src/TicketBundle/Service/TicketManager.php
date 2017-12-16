<?php
namespace TicketBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use TicketBundle\Entity\Scheme;
use TicketBundle\Entity\Show;

/**
 * Description of TicketManager
 *
 * @author sebastian
 */
class TicketManager 
{
    protected $container;
    
    
    
    public function setContainer($container) {
        $this->container = $container;
    }
    
    public function getContainer() {
        return $this->container;
    }
    
    public function getManager() 
    {
        return $this->container->get('doctrine')->getManager();
    }
    
    /**
     * Create chart in seats.io to show seats to choice
     * @param Scheme $scheme
     */
    public function createChart(Scheme $scheme) 
    {
        //curl https://app.seats.io/api/charts \
        //-X POST -H 'Content-Type: application/json' -d '{ "secretKey": "225ff6a9-a8b1-43e0-ac36-44b84e5af818", chart: {"name": "my chart"}}'
        $data = '{
            "secretKey": "'.$this->getServerKey().'",
            "chart": {
                 "name": "'.$scheme->getName().'",
                 "venueType": "MIXED",
                 "categories": [
                    { "key": 1, "label": "Category 1", "color": "#aaaaaa"},
                    { "key": 2, "label": "Category 2", "color": "#bbbbbb"}
                ]
             }
        }';
       
        $answer = $this->call($data, 'charts');
        $scheme->setSchemeKey($answer->key);
        $this->getManager()->flush();
    }
    
    /**
     * Create event in seats.io and related with a chart (scheme)
     * @param Show $show
     */
    public function createEvent(Show $show)
    {
        //curl https://app.seats.io/api/linkChartToEvent -v \
        //-X POST \
        //-H "Content-Type: application/json" \
        //-d "{'chartKey': '898c462b-6351-4b65-b494-70e754f26649', 'eventKey': '1', 'secretKey': '225ff6a9-a8b1-43e0-ac36-44b84e5af818'}"
            
        $data = "{
            'chartKey': '".$show->getEvent()->getScheme()->getSchemeKey()."',
            'eventKey': '".$show->getId()."',
            'secretKey': '".$this->getServerKey()."'
        }";
        $answer = $this->call($data, 'linkChartToEvent');
        
        if($answer->key == $show->getId()) return true;
            
        return false;
    }
    
    public function call($data, $endpoint)
    {
        // abrimos la sesión cURL
        $ch = curl_init();
        // definimos la URL a la que hacemos la petición
        curl_setopt($ch, CURLOPT_URL,"https://app.seats.io/api/".$endpoint);
        // indicamos el tipo de petición: POST
        curl_setopt($ch, CURLOPT_POST, TRUE);
        // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        // cerramos la sesión cURL
        curl_close ($ch);

        print_r($remote_server_output);die;
        // hacemos lo que 
        // queramos con los datos recibidos
        // por ejemplo, los mostramos
        $answer = json_decode($remote_server_output);
        return $answer;
    }
    
    public function getServerKey()
    {
        $em = $this->container->get('doctrine')->getManager();
        $parameter = $em->getRepository('CoreBundle:Parameter')->findOneByParameter('seats.io');
        $params = json_decode($parameter->getValue());
        
        return $params->secret_key;
    }
}
