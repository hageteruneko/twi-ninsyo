<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use \Firebase\JWT\JWT;

class ProductsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        if ($this->session->has("jwt") ) {
            $jwt = $this->session->get("jwt");
        }else{
            header("Location: ".'./index.phtml');
        }

        try{
            // デコード 
            $decoded = JWT::decode($jwt, KEY, array('HS256'));
            $name = $decoded->name;
            $this->view->name = $name;
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo json_encode(array(
              'message' => $message
            ));
            exit;
            header("Location: ".'./error.phtml');
        }

        $this->view->name = $name;
    }


    /**
     * Searches for products
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Products', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $products = Products::find($parameters);
        if (count($products) == 0) {
            $this->flash->notice("The search did not find any products");

            $this->dispatcher->forward([
                "controller" => "products",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $products,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a product
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product = Products::findFirstByid($id);
            if (!$product) {
                $this->flash->error("product was not found");

                $this->dispatcher->forward([
                    'controller' => "products",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product->id;

            $this->tag->setDefault("id", $product->id);
            $this->tag->setDefault("name", $product->name);
            $this->tag->setDefault("manual", $product->manual);
            $this->tag->setDefault("price", $product->price);
            $this->tag->setDefault("image", $product->image);
            $this->tag->setDefault("raw_data", $product->raw_data);
            $this->tag->setDefault("extension", $product->extension);
            
        }
    }

    /**
     * Creates a new product
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'index'
            ]);

            return;
        }

        $product = new Products();

        if($this->request->hasFiles()){
            //アップロードファイルがあるかどうかをチェックします。
            $dir_path = BASE_PATH.'\public\img';

                foreach ($this->request->getUploadedFiles() as $file) {
                //アップロードされたファイルを取得し、移動させます。
                $file->moveTo($dir_path. DIRECTORY_SEPARATOR . $file->getName());
                $product->image = $file->getName();
                //画像ファイルの指定
                $img_file = $dir_path."\/".$file->getName();
                //拡張子の取得
                $file_info = pathinfo($img_file);
                $img_extension = strtolower($file_info['extension']);
                $product->extension = $img_extension;
                    if($img_extension='png'||$img_extension='PNG'){       
                        $product->id = $this->request->getPost("id");
                        $product->name = $this->request->getPost("name");
                        $product->manual = $this->request->getPost("manual");
                        $product->price = $this->request->getPost("price");
                        $product->rawData = $this->request->getPost("raw_data");
                    }else{
                        echo "pngfile or jpegfile please";
                    }
                }
        }

        if (!$product->save()) {
            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'new'
            ]);

            return;
        }
        $this->flash->success("product was created successfully");
        
        $this->dispatcher->forward([
            'controller' => "products",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product = Products::findFirstByid($id);

        if (!$product) {
            $this->flash->error("product does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'index'
            ]);

            return;
        }

        if($this->request->hasFiles()){
            //アップロードファイルがあるかどうかをチェックします。
            $dir_path = BASE_PATH.'\public\img\/';

                foreach ($this->request->getUploadedFiles() as $file) {
                //アップロードされたファイルを取得し、移動させます。
                $file->moveTo($dir_path. DIRECTORY_SEPARATOR . $file->getName());
                $product->image = $file->getName();
                //画像ファイルの指定
                $img_file = $dir_path."\/".$file->getName();
                //拡張子の取得
                $file_info = pathinfo($img_file);
                $img_extension = strtolower($file_info['extension']);
                $product->extension = $img_extension;
                }
        }

        $product->id = $this->request->getPost("id");
        $product->name = $this->request->getPost("name");
        $product->manual = $this->request->getPost("manual");
        $product->price = $this->request->getPost("price");
        $product->rawData = $this->request->getPost("raw_data");


        

        if (!$product->save()) {

            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'edit',
                'params' => [$product->id]
            ]);

            return;
        }

        $this->flash->success("product was updated successfully");

        $this->dispatcher->forward([
            'controller' => "products",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $product = Products::findFirstByid($id);
        if (!$product) {
            $this->flash->error("product was not found");

            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product->delete()) {

            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "products",
                'action' => 'search'
            ]);

            return;
        }
        $dir_path = BASE_PATH.'\public\img';
        $img_file = $dir_path."\/".$product->image;
        if (unlink($img_file)){
            echo $img_file.'の削除に成功しました。';
        }else{
            echo $img_file.'の削除に失敗しました。';
        }
        $this->flash->success("product was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "products",
            'action' => "index"
        ]);
    }
}

