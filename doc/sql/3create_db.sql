CREATE TABLE boards (
	board_no				INT				NOT NULL		PRIMARY KEY AUTO_INCREMENT
-- ,user_no				INT				NOT NULL
	,board_title		VARCHAR(100) 	NOT NULL
	,board_content		VARCHAR(1000)	NOT NULL
	,board_img			VARCHAR(200)	NULL 
	,board_chkbox		INT				NOT NULL 	DEFAULT 0
	,created_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,updated_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,deleted_at			DATETIME			NULL 
);



CREATE TABLE memos (
	memo_no				INT				NOT NULL		PRIMARY KEY AUTO_INCREMENT
	,memo_content		VARCHAR(200)	NOT NULL
	,memo_chkbox		INT				NOT NULL		DEFAULT 0
	,created_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,updated_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,deleted_at			DATETIME			NULL 
);

CREATE TABLE users (
	user_no				INT				NOT NULL		PRIMARY KEY AUTO_INCREMENT
	,user_name			VARCHAR(50) 	NOT NULL
	,created_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,updated_at			DATETIME			NOT NULL 	DEFAULT CURRENT_TIMESTAMP()
	,deleted_at			DATETIME			NULL 
);



-- ALTER TABLE boards ADD CONSTRAINT fk_boards_boads_no FOREIGN KEY (board_no) REFERENCES users(user_no);
