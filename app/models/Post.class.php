<?php
class Post {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function getPostsCount(){
		/*$page = 1;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}*/
		$this->db->query('SELECT COUNT(*) FROM posts'); // Add   + bind 
		//$this->db->bind(':start_from', $start_from);
		//$this->db->bind(':records_per_page', $records_per_page);
		$results =  $this->db->resultSet();
		return ($results);
	}

	public function getPosts($page){
		/*$page = 1;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}*/
		$records_per_page = 10;
		$start_from = 0;
		if ($page !== 1){
			$start_from = ($page - 1) * $records_per_page;
		}
		$this->db->query('SELECT *,
							posts.id as postId,
							users.id as userId,
							posts.created_at as postCreated,
							users.created_at as userCreated
							FROM posts
							INNER JOIN users
							ON posts.user_id = users.id
							ORDER BY posts.created_at DESC
							LIMIT :start_from, :records_per_page
							'); // Add   + bind 
		$this->db->bind(':start_from', $start_from);
		$this->db->bind(':records_per_page', $records_per_page);
		$results =  $this->db->resultSet();
		return ($results);
	}

	public function getComments(){
		$this->db->query('SELECT *
							FROM comments
							INNER JOIN users
							ON comments.user_id = users.id
							ORDER BY comments.created_at ASC
							');

		$results =  $this->db->resultSet();
		return ($results);
	}

	public function getLikes(){
		$this->db->query('SELECT *
							FROM likes
							INNER JOIN users
							ON likes.user_id = users.id
							ORDER BY likes.created_at ASC
							');

		$results =  $this->db->resultSet();
		return ($results);
	}

	public function addPost($data){
		$this->db->query('INSERT INTO posts (title, user_id, body, img) VALUES (:title, :user_id, :body, :img)');
		// Bind values
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':user_id', $data['user_id']);
		$this->db->bind(':body', $data['body']);
		$this->db->bind(':img', $data['image']);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Add comment
	public function addComment($data){
		print_r($data);
		$this->db->query('INSERT INTO comments (post_id, user_id, body) VALUES (:post_id, :user_id, :body)');
		// Bind values
		$this->db->bind(':post_id', $data['post']);
		$this->db->bind(':user_id', $data['user_id']);
		$this->db->bind(':body', $data['body']);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Tried doing this in separate file; didn't work.
	public function addLike($data){
		//print_r($data);
		// Add like to table.
		$this->db->query('INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)');
		// Bind values
		$this->db->bind(':post_id', $data['post']);
		$this->db->bind(':user_id', $data['user_id']);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Remove like from database.
	public function removeLike($data){
		//print_r($data);
		// Remove like from table.
		$this->db->query('DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id');
		// Bind values
		$this->db->bind(':post_id', $data['post']);
		$this->db->bind(':user_id', $data['user_id']);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	public function updatePost($data){
		$this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
		// Bind values
		$this->db->bind(':body', $data['body']);
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':id', $data['id']);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	public function getPostById($id){
		$this->db->query('SELECT * FROM posts WHERE id = :id');
		$this->db->bind(':id', $id);

		$row =$this->db->single();
		return ($row);
	}
	
	public function deletePost($id){
		$this->db->query('DELETE FROM posts WHERE id = :id');
		// Bind values
		$this->db->bind(':id', $id);

		// Execute
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}
}
?>