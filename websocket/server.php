<?php
	namespace Live;

	require __DIR__ . '/vendor/autoload.php';
	use Ratchet\MessageComponentInterface;
	use Ratchet\ConnectionInterface;

	class Form implements MessageComponentInterface {
	    protected $clients;

	    public function __construct() {
	        $this->clients = new \SplObjectStorage;
	    }

	    public function onOpen(ConnectionInterface $conn) {
	        // Store the new connection to send messages to later
	        $this->clients->attach($conn);

	    }


	    public function onMessage(ConnectionInterface $from, $msg) {
	        foreach ($this->clients as $client) {
	            if ($from !== $client) {
	                // The sender is not the receiver, send to each client connected
	                $client->send($msg);
	            }
	            else{
	            	$client->send(1);
	            	
	            }
	        }
	    }

	    public function onClose(ConnectionInterface $conn) {
	        // The connection is closed, remove it, as we can no longer send it messages
	        $this->clients->detach($conn);
	    }

	    public function onError(ConnectionInterface $conn, \Exception $e) {
	        echo "An error has occurred: {$e->getMessage()}\n";

	        $conn->close();
	    }
	}

	use Ratchet\Server\IoServer;
	use Ratchet\WebSocket\WsServer;
	use Live\Form;

    $server = IoServer::factory(
        new WsServer(
	        new Form()
	        )
      , 8000
    );

    $server->run();
    echo 'server running';
 
    