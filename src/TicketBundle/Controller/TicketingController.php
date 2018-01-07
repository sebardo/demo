<?php

namespace TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TicketingController extends Controller
{
     /**
     * @Route("/videos", name="admin_post_video")
     * @Template()
     */
    public function videoAction(Request $request)
    {

        $OAUTH2_CLIENT_ID = $this->container->getParameter('client_id');
        $OAUTH2_CLIENT_SECRET = $this->container->getParameter('client_secret');;

        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . '/admin/posts/videos', FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        if (isset($_GET['code'])) {
          if (strval($_SESSION['state']) !== strval($_GET['state'])) {
            die('The session state did not match.');
          }

          $client->authenticate($_GET['code']);
          $_SESSION['token'] = $client->getAccessToken();
          header('Location: ' . $redirect);
        }

        if (isset($_SESSION['token'])) {
          $client->setAccessToken($_SESSION['token']);
        }

        // Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {
          try{
            // REPLACE this value with the path to the file you are uploading.
            $videoPath = __DIR__."/../Resources/public/test/echo.mp4";

            // Create a snippet with title, description, tags and category ID
            // Create an asset resource and set its snippet metadata and type.
            // This example sets the video's title, description, keyword tags, and
            // video category.
            $snippet = new Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle("Test title");
            $snippet->setDescription("Test description");
            $snippet->setTags(array("tag1", "tag2"));

            // Numeric video category. See
            // https://developers.google.com/youtube/v3/docs/videoCategories/list 
            $snippet->setCategoryId("22");

            // Set the video's status to "public". Valid statuses are "public",
            // "private" and "unlisted".
            $status = new Google_Service_YouTube_VideoStatus();
            $status->privacyStatus = "public";

            // Associate the snippet and status objects with a new video resource.
            $video = new Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);

            // Specify the size of each chunk of data, in bytes. Set a higher value for
            // reliable connection as fewer chunks lead to faster uploads. Set a lower
            // value for better recovery on less reliable connections.
            $chunkSizeBytes = 1 * 1024 * 1024;

            // Setting the defer flag to true tells the client to return a request which can be called
            // with ->execute(); instead of making the API call immediately.
            $client->setDefer(true);

            // Create a request for the API's videos.insert method to create and upload the video.
            $insertRequest = $youtube->videos->insert("status,snippet", $video);

            // Create a MediaFileUpload object for resumable uploads.
            $media = new Google_Http_MediaFileUpload(
                $client,
                $insertRequest,
                'video/*',
                null,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize(filesize($videoPath));


            // Read the media file and upload it chunk by chunk.
            $status = false;
            $handle = fopen($videoPath, "rb");
            while (!$status && !feof($handle)) {
              $chunk = fread($handle, $chunkSizeBytes);
              $status = $media->nextChunk($chunk);
            }

            fclose($handle);

            // If you want to make other calls after the file upload, set setDefer back to false
            $client->setDefer(false);


            $htmlBody .= "<h3>Video Uploaded</h3><ul>";
            $htmlBody .= sprintf('<li>%s (%s)</li>',
                $status['snippet']['title'],
                $status['id']);

            $htmlBody .= '</ul>';

          } catch (Google_Service_Exception $e) {
            $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          } catch (Google_Exception $e) {
            $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          }

          $_SESSION['token'] = $client->getAccessToken();
        } else {
          // If the user hasn't authorized the app, initiate the OAuth flow
          $state = mt_rand();
          $client->setState($state);
          $_SESSION['state'] = $state;

          $authUrl = $client->createAuthUrl();
          $htmlBody = "<h3>Authorization Required</h3> <p>You need to <a href=".$authUrl.">authorize access</a> before proceeding.<p>";
 
        }
        
        return array('htmlBody' => $htmlBody);
        
    }
    
    /**
     * @Route("/tickets", name="ticket_ticketing_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $events = $em->getRepository('TicketBundle:Event')->findBy(array());
        return array(
            'events' => $events
        );
    }
    
    /**
     * @Route("/events/{slug}", name="ticket_ticketing_event")
     * @Template()
     */
    public function eventAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('TicketBundle:Event')->findOneBySlug($slug);
        return array(
            'event' => $event
        );
    }
    
    /**
     * @Route("/events/{slug}/{sector}", name="ticket_ticketing_eventsector")
     * @Template("TicketBundle:Ticketing:event.html.twig")
     */
    public function eventSectorAction($slug, $sector)
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('TicketBundle:Event')->findOneBySlug($slug);
        $sector = $em->getRepository('TicketBundle:Sector')->findOneBySlug($sector);
        
        return array(
            'event' => $event,
            'sector' => $sector
        );
    }
}
