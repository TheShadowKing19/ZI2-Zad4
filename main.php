<?php
require_once __DIR__ . '/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;


try {
    //Połączenie
    $connectionParams = array(
        'dbname' => 'jm50478',
        'user' => 'jm50478',
        'password' => 'rL6hTcSR',
        'host' => 'db.zut.edu.pl',
        'driver' => 'pdo_mysql',
    );
    $conn = DriverManager::getConnection($connectionParams);

    //Zad 4

    $queryBuilder = $conn->createQueryBuilder();
    $queryBuilder->select('customerName, creditLimit')
        ->from('customers')
        ->where("customers.country = 'USA'")
        ->orderBy('creditLimit','DESC');
    $stmt = $queryBuilder->executeQuery();
    $results = $stmt->fetchAllAssociative();
    foreach ($results as $row) {
        echo $row['customerName'] . " " . $row['creditLimit'] . "\n";
    }

    $queryBuilder2 = $conn->createQueryBuilder();
    $queryBuilder2->select('c.customerNumber, c.customerName, e.firstName, e.lastName')
        ->from('customers', 'c')
        ->innerJoin('c','employees', 'e', 'c.salesRepEmployeeNumber = e.employeeNumber');
    $stmt2 = $queryBuilder2->executeQuery();
    $results2 = $stmt2->fetchAllAssociative();
    foreach ($results2 as $row2) {
        echo $row2['customerNumber'] . " " . $row2['customerName'] . " " . $row2['firstName'] . " " . $row2['lastName'] . "\n";
    }
//    Zad 5
    $tableName = 'my_new_table';
    $schema = new Schema();
    $tab = $schema->createTable($tableName);
    $tab->addColumn('id', 'integer',['autoincrement'=>true]);
    $tab->addColumn('napis', 'string',['length'=>64]);
    $tab->addColumn('liczba', 'integer');
    $tab->setPrimaryKey(['id']);
    $sql = $schema->toSql($conn->getDatabasePlatform());
    $conn->executeStatement($sql[0]);

    $queryBuilder3 = $conn->createQueryBuilder();
    $queryBuilder3->insert('my_new_table')
        ->values([
            'napis' => '?',
            'liczba' => '?',
        ])
        ->setParameters(['jeden', 01]);
    $queryBuilder3->executeStatement();
    $queryBuilder3->setParameters([
        1 => 'dwa',
        2 => 02
    ]);
    $queryBuilder3->executeStatement();
}
catch (Exception $e)
{
    print $e->getMessage();
    die();
}
