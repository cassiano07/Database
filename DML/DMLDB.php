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
			if($count == 0)
			{
				$query_fields = (string)$field;	
			}
			else
			{
				$query_fields = $query_fields.", ".$field;
			}

			if(in_array($field, $data_type['columns_string']))
			{
				$value = "'".(string)$values[$count]."'";
			}
			else
			{
				$value = $values[$count];
			}

			if($count == 0)
			{
				$query_values = (string)$value;	
			}
			else
			{
				$query_values = $query_values.", ".$value;
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

		$count = 0;

		if($fields_to_be_changed && $New_field_value)
		{
			foreach($fields_to_be_changed as $changed_field)
			{
				if(in_array($changed_field, $data_type['columns_string']))
				{
					$value = "'".(string)$New_field_value[$count]."'";	
				}
				elseif((string)$operator[$count] == 'in')
				{
					$value = (string)$New_field_value[$count];
				}
				else
				{
					$value = (int)$New_field_value[$count];
				}


				if($count == 0)
				{
					$fields_being_changed = (string)$changed_field." = ".$value;
				}
				else
				{
					$fields_being_changed = $fields_being_changed.", ".(string)$changed_field." = ".$value;
				}
			
				$count++;
			}

			$count2 = 0;
			$Conditions = '';

			foreach($Condition_field as $C_field)
			{
				if(in_array($C_field, $data_type['columns_string']) || (string)$operator[$count2] == 'like')
				{
					$value = "'".(string)$Condition_value[$count2]."'";	
				}
				elseif((string)$operator[$count2] == 'in')
				{
					$value = (string)$Condition_value[$count2];
				}
				else
				{
					$value = (int)$Condition_value[$count2];
				}

				if($count2 == 0)
				{
					$Conditions = " WHERE ".(string)$C_field." ".(string)$operator[$count2]." ".$value;
				}
				else
				{
					$Conditions = $Conditions." AND ".(string)$C_field." ".(string)$operator[$count2]." ".$value;
				}
			
				$count2++;
			}
		}

		$data = Database::execute($query.$fields_being_changed.$Conditions);
		return $query.$fields_being_changed.$Conditions;
	}

	function name()
	{
	    echo "Meu nome é " , get_class($this) , "\n";
	}
}


$teste = DMLDB::Update('user',['name'], ['tyi'], ['name', 'password'], ['=','!='], ['kk', '26293768']);
print_r($teste);





