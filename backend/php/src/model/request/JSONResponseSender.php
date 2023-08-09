<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\request;

    final class JSONResponseSender {
        public function sendResponse(Response $response) : never {
            http_response_code($response->getCode());
            $this->setHeaders();
            echo json_encode(array_merge(
                [ "status" => $response->getCode() ],
                $response->getBody()
            ));
            exit;
        }

        private function setHeaders() {
            header("Content-Type: application/json");
        }
    }
?>
