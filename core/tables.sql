-- Admin users
CREATE TABLE `feo_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Election management tables
CREATE TABLE `tbl_election` (
  `elect_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `duration_from` datetime NOT NULL,
  `duration_to` datetime NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`elect_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Shareholders/Users table
CREATE TABLE `tbl_shareholder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `tin` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `shares` decimal(10,2) NOT NULL, -- Number of shares/voting points
  `security_code` varchar(255) DEFAULT NULL,
  `voted_on` datetime DEFAULT NULL, -- For board election
  `voted_on2` datetime DEFAULT NULL, -- For agenda items
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Candidates table
CREATE TABLE `tbl_candidate` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `candidate_info` text,
  `position_id` int(11) NOT NULL,
  `photo` mediumblob, -- For candidate photos
  `imageType` varchar(255),
  PRIMARY KEY (`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Board of Directors Voting table
CREATE TABLE `tbl_vote` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `points` decimal(10,2) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Temporary votes table (for vote in progress)
CREATE TABLE `tbl_temp_vote` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `points` decimal(10,2) NOT NULL,
  PRIMARY KEY (`temp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Agenda Items table
CREATE TABLE `tbl_agenda` (
  `agenda_id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_item` text NOT NULL,
  `vote` int(11) NOT NULL,
  PRIMARY KEY (`agenda_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Temporary agenda votes table (for vote in progress)
CREATE TABLE `tbl_agenda_vote` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `vote` varchar(1) NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Temporary agenda votes table (for vote in progress)
CREATE TABLE `tbl_agenda_temp_vote` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `vote` varchar(1) NOT NULL,
  PRIMARY KEY (`temp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE VIEW results AS 
SELECT 
    tbl_candidate.candidate_id AS candidate_id, 
    tbl_candidate.display_name AS display_name, 
    COALESCE(SUM(tbl_vote.points), 0) AS total_votes 
FROM tbl_candidate 
LEFT JOIN tbl_vote ON tbl_candidate.candidate_id = tbl_vote.candidate_id 
GROUP BY candidate_id, display_name;