<?php

class Database
{
	// Faz a conexão com o banco de dados
	private function connect()
	{
		$user = "root";
		$password = "";
		$database = "AnimeBR";
		$hostname = "localhost"; 
		 
		# Conecta com o servidor de banco de dados 
		$mysqli = mysqli_connect( $hostname, $user, $password ) or die( ' Erro na conexão ' );
		$mysqli->select_db($database);

		if (mysqli_connect_errno())
		{
		    printf("Conexão falhou: %s\n", mysqli_connect_error());
		    exit();
		}
		
		return $mysqli;
	}

	// Retorna os campos de uma tabela.
	public static  function Data_Type($table)
	{
		$columns_string = [];
		$columns = [];
		$mandatory_columns = [];
		$type_string = ['CHAR', 'VARCHAR', 'BINARY', 'VARBINARY', 'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB', 'TINYTEXT', 'TEXT', 'MEDIUMTEXT', 'LONGTEXT', 'ENUM', 'SET', 'DATE', 'DATETIME', 'TIME', 'TIMESTAMP', 'YEAR'];

		$table_specifications = 'SHOW COLUMNS FROM '.$table;
		$specifications = Database::query($table_specifications, 'data');

		foreach ($specifications as $data_table)
		{
			$type = explode("(", $data_table['Type']);
			$type = strtoupper($type[0]);

			if(in_array($type, $type_string))
			{
				array_push($columns_string, $data_table['Field']);
			}

			if(strtoupper($data_table['Null']) ==  'NO' && $data_table['Default'] == '')
			{
				array_push($mandatory_columns, $data_table['Field']);
			}

			array_push($columns, $data_table['Field']);
		}

		$columns_full = ['columns' => $columns, 'columns_string' => $columns_string, 'mandatory_columns' => $mandatory_columns];

		return $columns_full;
	}

	// Executar querys de update e insert.
	public static function execute($query)
	{
		// Para executar qualquer query de insert e update.
		$mysqli = self::connect();
		$stmt = $mysqli->prepare($query);
		$result = $stmt->execute();
		$stmt->close();
		$mysqli->close();

		return $result; // retorna 1 para sucesso.
	}

	//Executa querys de consultar.
	public static function query($query, $valor = "")
	{
		$mysqli =  self::connect();
		$result = $mysqli->query($query);

		if($valor == 'rows')
		{
			$count_rows = $mysqli->affected_rows; // Retorna o números de linhas trazidas na consultar
			return $count_rows;
		}
		
		if($valor == 'colum')
		{
			$count_colum = $mysqli->field_count;
			return $count_colum;
		}
		if($valor == 'data')
		{
			$data = $result->fetch_all(MYSQLI_ASSOC);
			return $data;	
		}
		if($valor == 'one_data')
		{
			$one_data = $result->fetch_object();
			return $one_data;
		}

		return "Especifique um segundo parametro na chamada da função: 'rows','colum', 'data','one_date'.";
	}
}
