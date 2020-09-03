<?php

include ('../DB/Database.php');

Class DMLDB
{
	public static function Consult($table,$field = [], $value_field = [], $operator = ['='], $data_result = 'data')
	{
		$query = "SELECT * FROM ".$table;

		$data_type = Database::Data_Type($table);

		$condicion = '';
		$count = 0;

		if($field && $value_field)
		{
			foreach($field as $one_field)
			{
				if(in_array($one_field, $data_type['columns_string']) || (string)$operator[$count] == 'like')
				{
					$value = "'".(string)$value_field[$count]."'";	
				}
				elseif((string)$operator[$count] == 'in')
				{
					$value = (string)$value_field[$count];
				}
				else
				{
					$value = (int)$value_field[$count];
				}


				if($count == 0)
				{
					$condicion = " WHERE ".(string)$one_field." ".(string)$operator[$count]." ".$value;
				}
				else
				{
					$condicion = $condicion." AND ".(string)$one_field." ".(string)$operator[$count]." ".$value;
				}
			
				$count++;
			}
		}

		$data = Database::query($query.$condicion, $data_result);
		return $data;
	}

	public static function Insert($table, $fields, $values)
	{
		$query_fields = '';
		$query_values = '';
		$count = 0;

		$data_type = Database::Data_Type($table);

		foreach ($fields as $field)
		{

			$value = $values[$count];

			if(in_array($field, $data_type['columns_string']))
			{
				$value = "'".(string)$values[$count]."'";
			}

			$query_fields = $query_fields.", ".$field;
			$query_values = $query_values.", ".$value;

			if($count == 0)
			{
				$query_fields = (string)$field;
				$query_values = (string)$value;
			}

			$count++;
		}

		$query = "INSERT INTO ".$table." (".$query_fields.") VALUES (".$query_values.")";

		$data = Database::execute($query);
		return $data;
	}

	public static function Update($table, $fields_to_be_changed, $New_field_value, $Condition_field, $operator, $Condition_value)
	{
		$query = "UPDATE ".$table." SET ";

		$data_type = Database::Data_Type($table);
		$fields_being_changed = '';
		$count = 0;


		foreach($fields_to_be_changed as $changed_field)
		{
			$value = $New_field_value[$count];

			if(in_array($changed_field, $data_type['columns_string']))
			{
				$value = "'".$New_field_value[$count]."'";
			}

			$fields_being_changed = $fields_being_changed.", ".$changed_field." = ".$value;

			if($count == 0)
			{
				$fields_being_changed = $changed_field." = ".$value;
			}

			$count++;
		}

		$count2 = 0;
		$Conditions = '';

		foreach($Condition_field as $C_field)
		{
			$value = $Condition_value[$count2];

			if(in_array($C_field, $data_type['columns_string']) || (string)$operator[$count2] == 'like')
			{
				$value = "'".$Condition_value[$count2]."'";
			}

			$Conditions = $Conditions." AND ".$C_field." ".$operator[$count2]." ".$value;

			if($count2 == 0)
			{
				$Conditions = " WHERE ".$C_field." ".$operator[$count2]." ".$value;
			}

			$count2++;
		}


		$data = Database::execute($query.$fields_being_changed.$Conditions);
		return $data;
	}
}




// TESTE PARA FUNÇÃO UPDATE
//$table = 'user';
//$fields_to_be_changed =  ['name', 'email', 'password'];
//$New_field_value = ['Joel', 'Joel@gmail.com', '654654'];
//$Condition_field = ['created_at', 'phone'];
//$operator = ['>', '='];
//$Condition_value = ['2020-08-02', '26293768'];

//$teste = DMLDB::Update($table, $fields_to_be_changed, $New_field_value, $Condition_field, $operator, $Condition_value);
//print_r($teste);





