# Load a product by id
```
$id = 1;

$product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
$product->debug();
```

# Fetch all value of an attribute
```
$config = $objectManager->create('\Magento\Eav\Model\Config');
$attribute = $config->getAttribute('catalog_category', 'name');

$connection = $objectManager->create('Magento\Framework\App\ResourceConnection')->getConnection();
$select = $connection->select()->from(
            $attribute->getBackend()->getTable(),
            ['value']
        )->where(
            'attribute_id = ?',
            (int)$attribute->getId()
        );

$data = $connection->fetchCol($select);
```
