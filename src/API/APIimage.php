<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Symfony\Component\String\ByteString;

use Symfony\Component\WebRTC\RtcPeerConnection;

class WebRTCController extends AbstractController
{
    /**
     * @Route("/webrtc", name="webrtc")
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        $peerConnection = new RtcPeerConnection();

        // Handle incoming ICE candidates
        $peerConnection->onicecandidate = function($event) use ($session) {
            $candidate = $event->candidate;
            if ($candidate !== null) {
                $candidates = $session->get('ice_candidates', []);
                $candidates[] = $candidate;
                $session->set('ice_candidates', $candidates);
            }
        };

        // Handle incoming SDP offer
        $peerConnection->onoffer = function($event) use ($peerConnection) {
            $offer = $event->offer;
            $peerConnection->setLocalDescription($peerConnection->createAnswer($offer));
        };

        // Handle incoming SDP answer
        $peerConnection->onanswer = function($event) use ($peerConnection) {
            $answer = $event->answer;
            $peerConnection->setRemoteDescription($answer);
        };

        // Render the template
        return $this->render('webrtc/index.html.twig');
    }

    /**
     * @Route("/webrtc/sdp", name="webrtc_sdp")
     */
    public function sdp(Request $request, SessionInterface $session): JsonResponse
    {
        $sdp = new ByteString($request->getContent());
        $peerConnection = $session->get('peer_connection');
        if (!$peerConnection) {
            $peerConnection = new RtcPeerConnection();
            $session->set('peer_connection', $peerConnection);
        }
        $answer = $peerConnection->createAnswer($sdp);
        $peerConnection->setLocalDescription($answer);
        $candidates = $session->get('ice_candidates', []);
        return new JsonResponse(['sdp' => $answer, 'ice_candidates' => $candidates]);
    }
}
