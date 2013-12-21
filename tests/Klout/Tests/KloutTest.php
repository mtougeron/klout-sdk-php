<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Tests;

use Klout\Klout;

class KloutTest extends \PHPUnit_Framework_TestCase
{

    protected $apiKey = 'abc';

    protected $klout;
    protected $clientMock;

    protected $baseUrl;

    public function setUp()
    {
        $this->klout = new Klout($this->apiKey);
        $this->baseUrl = $this->klout->getClient()->getBaseUrl();
    }

    public function tearDown()
    {
        unset($this->klout, $this->clientMock, $this->baseUrl);
    }

    protected function setupMockClient()
    {

        $clientMock = $this->getMock('\Guzzle\Http\Client', array(), array($this->baseUrl));
        $clientMock->expects($this->any())
            ->method('send')
            ->with($this->anything())
            ->will($this->returnCallback(array($this, 'clientMockSendCallback')));
        $clientMock->expects($this->any())
            ->method('get')
            ->with($this->anything())
            ->will($this->returnCallback(array($this, 'clientMockGetCallback')));

        $this->clientMock = $clientMock;

        $this->klout->setClient($this->clientMock);
    }

    public function getResponseData($effectiveUrl)
    {
        if (stripos($effectiveUrl, 'identity') !== false) {
            $data = array(
                'id' => '615089',
                'network' => 'ks'
            );
        } elseif (stripos($effectiveUrl, 'influence') !== false) {
            $json = <<< JSONEND
{"myInfluencers":[{"entity":{"id":"488122","payload":{"kloutId":"488122","nick":"IGN","score":{"score":98.86529555861902},"scoreDeltas":{"dayChange":-0.003160288260261268,"weekChange":0.00228157874551016,"monthChange":-0.026759703182619887}}}},{"entity":{"id":"25614227645593185","payload":{"kloutId":"25614227645593185","nick":"foursquare","score":{"score":85.98569166814235},"scoreDeltas":{"dayChange":-0.032927498853794646,"weekChange":-0.10004936752793014,"monthChange":0.6862849101517554}}}},{"entity":{"id":"1119398","payload":{"kloutId":"1119398","nick":"mattcutts","score":{"score":84.39648155891005},"scoreDeltas":{"dayChange":-9.966295928336422E-4,"weekChange":-0.09137830864020202,"monthChange":-0.078982350269726}}}},{"entity":{"id":"1216953","payload":{"kloutId":"1216953","nick":"timoreilly","score":{"score":82.46907091905061},"scoreDeltas":{"dayChange":0.03544329811455782,"weekChange":-0.19809374269499358,"monthChange":2.685103100807339}}}},{"entity":{"id":"28147502437750250","payload":{"kloutId":"28147502437750250","nick":"Astro_Ron","score":{"score":81.14581167834672},"scoreDeltas":{"dayChange":-0.013939578296216837,"weekChange":0.203046234143045,"monthChange":1.133135100983381}}}}],"myInfluencees":[{"entity":{"id":"319956","payload":{"kloutId":"319956","nick":"ak2webd3","score":{"score":53.08068756174366},"scoreDeltas":{"dayChange":-0.12339887174051256,"weekChange":0.25338737321688143,"monthChange":2.7332117603239467}}}},{"entity":{"id":"523198","payload":{"kloutId":"523198","nick":"lobster1234","score":{"score":40.136326444209715},"scoreDeltas":{"dayChange":-0.1913122224627628,"weekChange":-0.07318929176346245,"monthChange":1.1175124205332168}}}},{"entity":{"id":"47569276047152723","payload":{"kloutId":"47569276047152723","nick":"jtai","score":{"score":31.298618212795574},"scoreDeltas":{"dayChange":-0.05895848190631625,"weekChange":-0.6229702686252807,"monthChange":4.671507679678697}}}},{"entity":{"id":"34621426900950220","payload":{"kloutId":"34621426900950220","nick":"pikachiao","score":{"score":28.414095143082278},"scoreDeltas":{"dayChange":-0.117468509584441,"weekChange":2.476541268597096,"monthChange":3.080323204753377}}}},{"entity":{"id":"34058476947259097","payload":{"kloutId":"34058476947259097","nick":"stozin","score":{"score":28.032137007453898},"scoreDeltas":{"dayChange":-0.28337115205339103,"weekChange":-1.0676044084073908,"monthChange":-1.4761516832294177}}}}],"myInfluencersCount":17,"myInfluenceesCount":8}
JSONEND;
            $data = json_decode($json, true);
        } elseif (stripos($effectiveUrl, 'topics') !== false) {
            $json = <<< JSONEND
[{"id":"8226386291923137474","displayName":"Software Development","name":"Software Development","slug":"software-development","imageUrl":"http://kcdn3.klout.com/static/images/icons/generic-topic.png","topicType":"sub"},{"id":"7188611684654363095","displayName":"MySQL","name":"MySQL","slug":"mysql","imageUrl":"http://kcdn3.klout.com/static/images/icons/generic-topic.png","topicType":"entity"},{"id":"9219221220892055214","displayName":"IGN","name":"IGN","slug":"ign","imageUrl":"http://kcdn3.klout.com/static/images/icons/generic-topic.png","topicType":"entity"},{"id":"7733103793947682695","displayName":"HTML 5","name":"HTML 5","slug":"html-5","imageUrl":"http://kcdn3.klout.com/static/images/icons/generic-topic.png","topicType":"entity"},{"id":"5144818194631006088","displayName":"Software","name":"Software","slug":"software","imageUrl":"http://kcdn3.klout.com/static/images/icons/generic-topic.png","topicType":"sub"}]
JSONEND;
            $data = json_decode($json, true);
        } else {
            $json = <<< JSONEND
{"kloutId":"615089","nick":"mtougeron","score":{"score":48.87713850934447},"scoreDeltas":{"dayChange":-0.070075364125195,"weekChange":-0.8043778004731834,"monthChange":-3.379512921850605}}
JSONEND;
            $data = json_decode($json, true);
        }

        return $data;
    }

    public function createResponseMock($effectiveUrl)
    {
        $data = $this->getResponseData($effectiveUrl);

        $responseMock = $this->getMock('\Guzzle\Http\Message\Response', array(), array(200));
        $responseMock->expects($this->any())
            ->method('json')
            ->will(
                $this->returnValue($data)
            );
        $responseMock->expects($this->any())
            ->method('getEffectiveUrl')
            ->will(
                $this->returnValue(
                    $effectiveUrl
                )
            );

        return $responseMock;
    }

    public function createRequestMock($effectiveUrl)
    {
        $requestMock = $this->getMock('\Guzzle\Http\Message\Request', array(), array('GET', $this->baseUrl));
        $requestMock->expects($this->any())
            ->method('send')
            ->will($this->returnValue($this->createResponseMock($effectiveUrl)));

        return $requestMock;
    }

    public function clientMockSendCallback()
    {
        $args = func_get_args();
        $responses = array();
        foreach ($args[0] as $request) {
            $responses[] = $request->send();
        }

        return $responses;
    }

    public function clientMockGetCallback()
    {
        $args = func_get_args();

        return $this->createRequestMock($this->baseUrl . '/' . $args[0]);
    }

    public function testCredentialsSet()
    {
        $klout = new Klout($this->apiKey);
        $client = $klout->getClient();
        $defaultOptions = $client->getDefaultOption('query');
        $this->assertArrayHasKey('key', $defaultOptions);
        $this->assertEquals($this->apiKey, $defaultOptions['key']);
    }

    public function testCredentialsNotSet()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $klout = new Klout(null);
    }

    public function testApiBaseUrlNotSet()
    {
        $client = $this->klout->getClient();
        $this->assertEquals('http://api.klout.com/v2', $client->getBaseUrl());
    }

    public function testConstructorSetValidApiBaseUri()
    {
        $klout = new Klout($this->apiKey, 'http://example.com');
        $client = $klout->getClient();
        $this->assertEquals('http://example.com', $client->getBaseUrl());
    }

    public function testConstructorSetInvalidApiBaseUri()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $klout = new Klout($this->apiKey, 'http_1://example.com');
    }

    public function testSetValidApiBaseUri()
    {
        $client = $this->klout->getClient();
        $this->assertNotEquals('http://example.com', $client->getBaseUrl());

        $this->klout->setApiBaseUri('http://example.com');
        $client = $this->klout->getClient();
        $this->assertEquals('http://example.com', $client->getBaseUrl());
    }

    public function testSetInvalidApiBaseUri()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->setApiBaseUri('http_1://example.com');
    }

    public function testInvalidNetworkNameValue()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork('asdqs dasd', 1234);
    }

    public function testInvalidNetworkName()
    {
        $this->setupMockClient();
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork('asdqsdasd', 1234);
    }

    public function testInvalidNetworkUserIdGooglePlus()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork(Klout::NETWORK_GOOGLE_PLUS, 'abc');
    }

    public function testInvalidNetworkUserIdTwitterId()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork(Klout::NETWORK_TWITTER_ID, 'abc');
    }

    public function testInvalidNetworkUserIdTwitter()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork(Klout::NETWORK_TWITTER, 'abc saf 2323');
    }

    public function testInvalidNetworkUserIdKlout()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork(Klout::NETWORK_KLOUT, 'abc');
    }

    public function testInvalidNetworkUserIdInstagram()
    {
        $this->setExpectedException('Klout\Exception\InvalidArgumentException');
        $this->klout->getUserByNetwork(Klout::NETWORK_INSTAGRAM, 'abc');
    }

    public function testValidNetworkUserIdGooglePlus()
    {
        $this->setupMockClient();
        $user = $this->klout->getUserByNetwork(Klout::NETWORK_GOOGLE_PLUS, "102371642188013001151");
        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_GOOGLE_PLUS));
        $this->assertEquals(2, $user->getIdentities()->count());
    }

    public function testValidNetworkUserIdTwitter()
    {
        $this->setupMockClient();
        $user = $this->klout->getUserByNetwork(Klout::NETWORK_TWITTER, "mtougeron");
        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_TWITTER));
        $this->assertEquals(2, $user->getIdentities()->count());
    }

    public function testValidNetworkUserIdTwitterId()
    {
        $this->setupMockClient();
        $user = $this->klout->getUserByNetwork(Klout::NETWORK_TWITTER_ID, "11850752");
        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_TWITTER_ID));
        $this->assertEquals(2, $user->getIdentities()->count());
    }

    public function testValidNetworkUserIdInstagram()
    {
        $this->setupMockClient();
        $user = $this->klout->getUserByNetwork(Klout::NETWORK_INSTAGRAM, "12345");
        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_INSTAGRAM));
        $this->assertEquals(2, $user->getIdentities()->count());
    }

    public function testValidNetworkUserIdKlout()
    {
        $this->setupMockClient();
        $user = $this->klout->getUserByNetwork(Klout::NETWORK_KLOUT, "615089");
        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_KLOUT));
        $this->assertEquals(1, $user->getIdentities()->count());
    }

    public function testGetUser()
    {
        $this->setupMockClient();
        $user = $this->klout->getUser("615089");

        $this->assertInstanceOf('Klout\Model\User', $user);
        $this->assertEquals("615089", $user->getKloutId());
        $this->assertEquals("mtougeron", $user->getNickname());

        $this->assertInstanceOf('Klout\Model\Identity', $user->getIdentity(Klout::NETWORK_KLOUT));
        $this->assertEquals(1, $user->getIdentities()->count());
        $this->assertInstanceOf('Klout\Collection\Identity', $user->getIdentities());

        $this->assertInstanceOf('Klout\Model\Score', $user->getScore());
        $this->assertNotEmpty($user->getScore()->getScore());

        $this->assertInstanceof('Klout\Collection\User', $user->getInfluencers());
        $this->assertNotEquals(0, count($user->getInfluencers()));

        $this->assertInstanceof('Klout\Collection\User', $user->getInfluencees());
        $this->assertNotEquals(0, count($user->getInfluencees()));

        $this->assertInstanceof('Klout\Collection\Topic', $user->getTopics());
        $this->assertNotEquals(0, count($user->getTopics()));

    }
}
