<?php

namespace Speed\Trade\Helper;

class PoolValidate {

  static function check2($json, $schema_file) {
    $schema = file_get_contents($schema_file);

    $result = \Jsv4::validate(json_decode($json), json_decode($schema));
  }

  static function check($json, $schema_file) {
    $retriever = new \JsonSchema\Uri\UriRetriever;
    $schema = $retriever->retrieve('file://' . $schema_file);

    $refResolver = new \JsonSchema\RefResolver($retriever);
    $refResolver->resolve($schema, 'file://' . dirname($schema_file));

    $validator = new \JsonSchema\Validator();
    $validator->check(json_decode($json), $schema);

    $result = array("valid" => true, "msg" => "正确");  
    if (!$validator->isValid()) {
      $messages = "";
      foreach ($validator->getErrors() as $error) {
        $messages .= sprintf("[%s] %s\n", $error['property'], $error['message']);
      }

      $result = array(
        "valid" => false,
        "msg" => "数据格式不正确: " . $messages
      );
    }

    return $result;
  }

}
