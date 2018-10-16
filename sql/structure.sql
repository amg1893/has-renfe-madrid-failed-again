create table hashtag_status (
    name varchar(100) NOT NULL,
    hashtag varchar(100) NOT NULL,
    last_id bigint(20),
    date_tweet varchar(255),
    update_date datetime
);

create table latest (
    last_id bigint(20),
    date_tweet varchar(255)
);