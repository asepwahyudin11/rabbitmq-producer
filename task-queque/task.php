<?php 
    require_once '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $data = implode(' ', array_slice($argv, 1));
    if (empty($data)) {
        $data = "Hello World!";
    }
    $msg = new AMQPMessage(
        $data,
        array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
    );

    $channel->basic_publish($msg, '', 'hello');

    echo ' [x] Sent ', $data, "\n";

    $channel->close();
    $connection->close();
?>