<?php
	class Model_search_book extends CI_Model{
		//constructor loads the database
		function __construct(){
			parent::__construct();
			$this->load->database();
		}
		 // Finds all books that match the passed string
		 //querry must be refined so that it can support tags such as tags that are found in tags table
		function find_suggestion($str, $category){
			if($category == "author"){
				$this->db->select('DISTINCT '.$category.'
					FROM book_author 
					WHERE '.$category.' LIKE \'%'.$str.'%\'
					LIMIT 5
					', FALSE);
			}
			else if($category == "subject"){
				$this->db->select('DISTINCT '.$category.'
					FROM book_subject 
					WHERE '.$category.' LIKE \'%'.$str.'%\'
					LIMIT 5
					', FALSE);
			}
			else if($category == "tag_name"){
				$this->db->select('DISTINCT '.$category.'
					FROM tag 
					WHERE '.$category.' LIKE \'%'.$str.'%\'
					LIMIT 5
					', FALSE);
			}
			else{
				$this->db->select('DISTINCT '.$category.'
					FROM book 
					WHERE '.$category.' LIKE \'%'.$str.'%\'
					LIMIT 5
					', FALSE);
			}
			return $this->db->get();
		}


		function addAnd($query,$and_check){
			if($and_check==true) $query=$query." and ";
			return $query;
		}

		//Finds all the books that match the data array
		//refine qeury so that it can fetch data of the book if it is reserved or not.
		function fetch_book_data($data,$limit,$start){
			$orCheck = false;
			$andCheck=false;
			//The first call of the function will skip the limit since we will be using it to compute for the total rows
			//this condition will be used later when we are fetching the actual data.
			if($limit>0){	//checks the limit if it is set to greater than 0.
				$this->db->limit($limit, $start);
			}
			//querry for the data fetching
			//As long as the $data['str'] is not an empty string, it will dominate over the advance search forms,
			$query='id, title, year_of_pub, type, no_of_available
				FROM book
				WHERE ';
			if($data['str'] !== ""){
				if($data['category'] == "author"){
					$query=$query.'id in
						(SELECT id
						FROM book_author
						WHERE author LIKE \'%'.$data['str'].'%\'
						ORDER BY levenshtein('.$data['category'].', \'%'.$data['str'].'%\'))';
				}
				else if($data['category'] == "subject"){
					$query=$query.'id in
						(SELECT id
						FROM book_subject
						WHERE subject LIKE \'%'.$data['str'].'%\'
						ORDER BY levenshtein('.$data['category'].', \'%'.$data['str'].'%\'))';
				}
				else if($data['category'] == "tag_name"){
					$query=$query.'id in
						(SELECT id
						FROM tag
						WHERE tag_name LIKE \'%'.$data['str'].'%\'
						ORDER BY levenshtein('.$data['category'].', \'%'.$data['str'].'%\'))';
				}
				else{
					$query=$query.$data['category'].' LIKE \'%'.$data['str'].'%\'
						ORDER BY levenshtein('.$data['category'].', \'%'.$data['str'].'%\')';	
				}
			}
				
			else{	//if $data['str'] is an empty string move to advance search inputs. This will call addOr to add an 'or' phrase if there are multiple inputs from the form, which is determined using orCheck variable
				
				if(isSet($data['title'])){
					$query=$query.'title LIKE \'%'.$data['title'].'%\'ORDER BY levenshtein(title, \'%'.$data['title'].'%\')';
					$andCheck=true;
				}
				if(isSet($data['year_of_pub'])){
					$query=$this->model_search_book->addAnd($query,$andCheck);
					$andCheck=false;
					$query=$query.'year_of_pub LIKE \'%'.$data['year_of_pub'].'%\'ORDER BY levenshtein(year_of_pub, \'%'.$data['year_of_pub'].'%\')';
					$andCheck=true;
				}
				if(isSet($data['author'])){
					$query=$this->model_search_book->addAnd($query,$andCheck);
					$andCheck=false;
					$query=$query.'id in
					(SELECT id
					FROM book_author
					WHERE author LIKE \'%'.$data['author'].'%\'ORDER BY levenshtein(author, \'%'.$data['author'].'%\'))';
					$andCheck=true;
				}
				if(isSet($data['subject'])){
					$query=$this->model_search_book->addAnd($query,$andCheck);
					$andCheck=false;
					$query=$query.'id in
					(SELECT id
					FROM book_subject
					WHERE subject LIKE \'%'.$data['subject'].'%\'ORDER BY levenshtein(subject, \'%'.$data['subject'].'%\'))';
					$andCheck=true;
				}
				if(isSet($data['tag_name'])){
					$query=$this->model_search_book->addAnd($query,$andCheck);
					$andCheck=false;
					$query=$query.'id in
					(SELECT id
					FROM tag
					WHERE tag_name LIKE \'%'.$data['tag_name'].'%\'ORDER BY levenshtein(tag_name, \'%'.$data['tag_name'].'%\'))';
					$andCheck=true;
				}
			}
			//execute query
			$this->db->select($query,FALSE);
			
			return $this->db->get();
		}

		function fetch_book_author($id){
			$query="author
			FROM book_author
			WHERE id LIKE '".$id."'";
			//execute query
			$this->db->select($query,FALSE);
			
			return $this->db->get();
		}
	}
