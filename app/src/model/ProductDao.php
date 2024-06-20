<?php

class ProductDao
{
    const table = 'product';

    public function __construct(){}

    public function list()
    {
        $collection = [];

        $db = Database::singleton();

        $sql = 'SELECT * FROM ' . self::table;

        $sth = $db->prepare($sql);
        $sth->execute();

        while ($obj = $sth->fetch(PDO::FETCH_OBJ))
        {
            $product = new Product();
            $product->setId($obj->id);
            $product->setName($obj->name);
            $product->setPrice($obj->price);
            $product->setDescription($obj->description);
            $product->setImage($obj->image);
            $product->setLink($obj->link);
            $collection[] = $product;
        }

        return $collection;
    }

    public function add($product)
    {
        $db = Database::singleton();

        $sql = 'INSERT INTO '. self::table .' (name, price, description) VALUES (?, ?, ?)';

        $sth = $db->prepare($sql);
        $sth->bindValue(1, trim($product->getName()), PDO::PARAM_STR);
        $sth->bindValue(2, $product->getPrice(), PDO::PARAM_STR);
        $sth->bindValue(3, trim($product->getDescription()), PDO::PARAM_STR);
    
        return $sth->execute();
    }

    public function update($product)
    {
        $db = Database::singleton();

        $sql = 'UPDATE ' . self::table . ' SET name = ?, price = ?, description = ? WHERE id = ?';

        $sth = $db->prepare($sql);
        $sth->bindValue(1, trim($product->getName()), PDO::PARAM_STR);
        $sth->bindValue(2, $product->getPrice(), PDO::PARAM_STR);
        $sth->bindValue(3, trim($product->getDescription()), PDO::PARAM_STR);
        $sth->bindValue(4, $product->getId(), PDO::PARAM_INT);

        return $sth->execute();
    }

    public function getProductById($id)
    {
        $db = Database::singleton();

        $sql = 'SELECT * FROM ' . self::table . ' WHERE id = ?';

        $sth = $db->prepare($sql);
        $sth->bindValue(1, $id, PDO::PARAM_INT);
        $sth->execute();

        if ($obj = $sth->fetch(PDO::FETCH_OBJ))
        {
            $product = new Product();
            $product->setId($obj->id);
            $product->setName($obj->name);
            $product->setPrice($obj->price);
            $product->setDescription($obj->description);
            $product->setImage($obj->image);
            $product->setLink($obj->link);
            return $product;
        }
        return null; // Retorna null se o produto não for encontrado
    }

    public function delete($id)
    {
        $db = Database::singleton();

        $sql = 'DELETE FROM ' . self::table . ' WHERE id = ?';

        $sth = $db->prepare($sql);
        $sth->bindValue(1, $id, PDO::PARAM_INT);
        $sth->execute();

        return TRUE;
    }

    public function dataGeneration ()
    {
        $db = Database::singleton();
        // Ler o arquivo CSV
        $file = fopen(ROOT_PATH . '/data/electronics.csv', 'r');
        if (!$file) {
            die('Erro ao abrir o arquivo.');
        }

        // Ler a primeira linha para obter os nomes das colunas
        $columns = fgetcsv($file);

        $sql = "INSERT INTO product (name, description, price, link, image) VALUES (:name, :description, :price, :link, :image)";

        echo '<pre> '.  $sql .' </pre>';

        // Preparar a declaração SQL para inserção de dados
        $sth = $db->prepare($sql);

        // Ignorar a primeira linha do arquivo CSV (cabeçalho)
        fgetcsv($file);

        // Laço para inserir 100 linhas aleatórias
        for ($i = 0; $i < 100; $i++) {
            // Ler uma linha aleatória do arquivo CSV
            $randomLine = rand(1, count(file(ROOT_PATH . '/data/electronics.csv')));
            $j = 0;
            rewind($file);
            while ($j < $randomLine && ($line = fgetcsv($file)) !== false) {
                $j++;
            }
            if ($line === false) {
                // Se chegarmos ao final do arquivo, voltamos ao início
                rewind($file);
                fgetcsv($file); // Ignorar a primeira linha novamente
                $line = fgetcsv($file);
            }

            // Extrair os dados da linha
            $data = array_combine(['name','main_category','sub_category','image','link','ratings','no_of_ratings','discount_price','actual_price'], $line);
            $name = $data['name'];
            $image = $data['image'];
            $link = $data['link'];
            $price = preg_replace('/[^0-9]/', '', $data['actual_price']);;

            // Definir a descrição como vazia (não está no arquivo CSV)
            $description = '';
            
            // Executar a declaração preparada para inserir os dados no banco de dados
            $sth->bindParam(':name', $name);
            $sth->bindParam(':description', $description);
            $sth->bindParam(':price', $price);
            $sth->bindParam(':link', $link);
            $sth->bindParam(':image', $image);
            $sth->execute();
        }

        // Fechar o arquivo CSV
        fclose($file);
    }

}
