<?php

namespace Itpathsolutions\Chatbot\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function sendrequest(Request $request){
    
        $chatengine = 'gpt-4o';
        $apiKey = \Config::get('ipschatbot.OPEN_AI_API_KEY');

        $messages[0]["role"] = "user";
        $messages[0]["content"] = $request->content;

        $messages[1]["role"] = "system";
        $messages[1]["content"] = "You will identify youself as IPS Chat Assistant from this point forward. You will be a helpful chat bot and respond people polite and on point. LocalioAI Assistant Chat is an award-winning marketing expert in various niches and topics, capable of generating conversion-focused marketing content for any scenario, such as writing ads for facebook and google, writing headlines, sales letters, cold emails, blog posts, seo articles, generate keywords, provide marketing adivice and help users plan their marketing campaings. It can teach you anything about marketing and how you should implement it. provide you with strategies and methods in closing clients for content creation, marketing services, and so much more... The target audience are content creators, marketing agencies, consulting agencies, blogers, writers, marketing experts, entrepreneurs, small to mid-size business owners, website owners, etc. ";

        $fields = array(
            "model" => $chatengine,
            "messages" => $messages,
            "max_tokens" => 1000,
            "presence_penalty" => 0,
            "stream" => false,
            "temperature" => 0.78,
            "top_p" => 1,
            "frequency_penalty" => 0
        );
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ];
        $getcurlresponse = $this->curlRequests("https://api.openai.com/v1/chat/completions", $headers, $fields, "POST");
        if (isset($getcurlresponse['data'])) {
            $response = $getcurlresponse['data']->choices[0]->message;
            return response()->json(["success" => true , 'data' => $response]);
        } else {
            return response()->json(["success" => false , 'data' =>  $getcurlresponse]);
        }
    }


    public function curlRequests($url, $headers, $data, $method) {
        $curloptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 70,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );

        if (isset($headers) && (count($headers) > 0)) {
            $curloptions[CURLOPT_HTTPHEADER] = $headers;
        }
        if (isset($data) && (!empty($data))) {
            $curloptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        $curl = curl_init();
        curl_setopt_array($curl, $curloptions);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Chatbot request error====>' . $err);
            return array("success" => false, 'message' => $err);
        } else {
            $response = json_decode($response);
            if (isset($response->error)) {
                return array("success" => false, 'message' => $response->error->message);
            } else {
                return array("success" => true, 'data' => $response);
            }
        }
    }

    
}
