-- --------------------------------------------------------
-- 호스트:                          127.0.0.1
-- 서버 버전:                        10.4.32-MariaDB - mariadb.org binary distribution
-- 서버 OS:                        Win64
-- HeidiSQL 버전:                  12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 테이블 cube_ai.admins 구조 내보내기
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '이름',
  `account` varchar(255) NOT NULL COMMENT '계정',
  `password` varchar(255) NOT NULL COMMENT '비밀번호',
  `remember_token` varchar(100) DEFAULT NULL COMMENT '토큰',
  `admin_level` tinyint(4) NOT NULL COMMENT '관리자 등급',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_account_unique` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.admin_otps 구조 내보내기
CREATE TABLE IF NOT EXISTS `admin_otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `last_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_otps_admin_id_foreign` (`admin_id`),
  CONSTRAINT `admin_otps_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.assets 구조 내보내기
CREATE TABLE IF NOT EXISTS `assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `coin_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '잔액',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assets_user_id_foreign` (`user_id`),
  KEY `assets_coin_id_foreign` (`coin_id`),
  CONSTRAINT `assets_coin_id_foreign` FOREIGN KEY (`coin_id`) REFERENCES `coins` (`id`),
  CONSTRAINT `assets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.asset_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `asset_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deposit_period` int(11) NOT NULL COMMENT '입금 반영 기간',
  `internal_period` int(11) NOT NULL COMMENT '내부이체 반영 기간',
  `tax_rate` decimal(20,9) NOT NULL COMMENT '세금 비율',
  `fee_rate` decimal(20,9) NOT NULL COMMENT '수수료 비율',
  `min_valid` decimal(20,9) NOT NULL COMMENT '최소 보유 금액',
  `min_withdrawal` decimal(20,9) NOT NULL COMMENT '최소 출금 금액',
  `withdrawal_days` varchar(50) DEFAULT NULL COMMENT '출금 가능 요일',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.asset_transfers 구조 내보내기
CREATE TABLE IF NOT EXISTS `asset_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `asset_id` bigint(20) unsigned NOT NULL,
  `type` enum('deposit','withdrawal','internal','manual_deposit','staking_refund') NOT NULL COMMENT '거래 타입',
  `status` enum('pending','waiting','completed','canceled','refunded') NOT NULL COMMENT '거래 상태',
  `tax` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '세금',
  `fee` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수수료',
  `amount` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래금액',
  `actual_amount` decimal(20,9) DEFAULT 0.000000000 COMMENT '실제금액',
  `before_balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래 전 잔액',
  `after_balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래 후 잔액',
  `txid` varchar(50) DEFAULT NULL COMMENT 'txid',
  `image_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '이미지 링크' CHECK (json_valid(`image_urls`)),
  `memo` varchar(255) DEFAULT NULL COMMENT '관리자 메모',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_transfers_user_id_foreign` (`user_id`),
  KEY `asset_transfers_asset_id_foreign` (`asset_id`),
  CONSTRAINT `asset_transfers_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`),
  CONSTRAINT `asset_transfers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.boards 구조 내보내기
CREATE TABLE IF NOT EXISTS `boards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `board_code` varchar(20) NOT NULL COMMENT '게시판 코드',
  `board_name` varchar(50) NOT NULL COMMENT '게시판 이름',
  `board_level` tinyint(4) DEFAULT NULL COMMENT '게시판 레벨',
  `is_comment` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '답글 여부',
  `is_popup` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '팝업 여부',
  `is_banner` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '배너 여부',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.coins 구조 내보내기
CREATE TABLE IF NOT EXISTS `coins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT '코드',
  `name` varchar(50) NOT NULL COMMENT '이름',
  `address` varchar(255) DEFAULT NULL COMMENT '입금 주소',
  `image_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '이미지 링크' CHECK (json_valid(`image_urls`)),
  `is_active` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '사용 여부',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.comments 구조 내보내기
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `tab` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '댓글 순번',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `content` text NOT NULL COMMENT '내용',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_board_id_foreign` (`board_id`),
  KEY `comments_post_id_foreign` (`post_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_admin_id_foreign` (`admin_id`),
  CONSTRAINT `comments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.deposit_toasts 구조 내보내기
CREATE TABLE IF NOT EXISTS `deposit_toasts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deposit_id` bigint(20) unsigned NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deposit_toasts_deposit_id_foreign` (`deposit_id`),
  CONSTRAINT `deposit_toasts_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `asset_transfers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.grade_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `grade_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` bigint(20) unsigned NOT NULL,
  `base_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '기본 매출',
  `self_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '개인 매출',
  `group_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '그룹 매출',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grade_policies_grade_id_foreign` (`grade_id`),
  CONSTRAINT `grade_policies_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `user_grades` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.incomes 구조 내보내기
CREATE TABLE IF NOT EXISTS `incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `coin_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '잔액',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incomes_user_id_foreign` (`user_id`),
  KEY `incomes_coin_id_foreign` (`coin_id`),
  CONSTRAINT `incomes_coin_id_foreign` FOREIGN KEY (`coin_id`) REFERENCES `coins` (`id`),
  CONSTRAINT `incomes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.income_transfers 구조 내보내기
CREATE TABLE IF NOT EXISTS `income_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `income_id` bigint(20) unsigned NOT NULL,
  `type` enum('deposit','withdrawal','trading_profit','subscription_bonus','staking_reward','referral_bonus','rank_bonus') DEFAULT NULL,
  `status` enum('pending','waiting','completed','canceled','refunded') NOT NULL COMMENT '거래 상태',
  `actual_amount` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '실제금액',
  `tax` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '세금',
  `fee` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수수료',
  `amount` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래금액',
  `before_balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래 전 잔액',
  `after_balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '거래 후 잔액',
  `memo` varchar(255) DEFAULT NULL COMMENT '관리자 메모',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `income_transfers_user_id_foreign` (`user_id`),
  KEY `income_transfers_income_id_foreign` (`income_id`),
  CONSTRAINT `income_transfers_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `incomes` (`id`),
  CONSTRAINT `income_transfers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.kakao_tokens 구조 내보내기
CREATE TABLE IF NOT EXISTS `kakao_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_role` varchar(255) NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kakao_tokens_user_role_unique` (`user_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.kyc_verifications 구조 내보내기
CREATE TABLE IF NOT EXISTS `kyc_verifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('id_card','passport','driver_license') NOT NULL COMMENT '인증 타입',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending' COMMENT '인증 상태',
  `nationality` varchar(50) NOT NULL COMMENT '국가',
  `given_name` varchar(255) NOT NULL COMMENT '본명',
  `surname` varchar(255) NOT NULL COMMENT '성',
  `id_number` varchar(255) NOT NULL COMMENT '신분증 번호',
  `image_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '이미지 경로' CHECK (json_valid(`image_urls`)),
  `date_of_birth` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '생년월일',
  `memo` varchar(255) DEFAULT NULL COMMENT '관리자 메모',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kyc_verifications_user_id_foreign` (`user_id`),
  CONSTRAINT `kyc_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.language_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `language_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL COMMENT '정책 타입',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '정책 내용' CHECK (json_valid(`content`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.message_keys 구조 내보내기
CREATE TABLE IF NOT EXISTS `message_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL COMMENT '카테고리',
  `key` varchar(255) NOT NULL COMMENT '키',
  `description` varchar(255) DEFAULT NULL COMMENT '설명',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=277 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.migrations 구조 내보내기
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.personal_access_tokens 구조 내보내기
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.policy_modify_logs 구조 내보내기
CREATE TABLE IF NOT EXISTS `policy_modify_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `policy_type` varchar(255) NOT NULL,
  `policy_id` bigint(20) unsigned NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `column_description` varchar(255) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.posts 구조 내보내기
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `board_id` bigint(20) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL COMMENT '제목',
  `content` longtext NOT NULL COMMENT '내용',
  `image_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '이미지 경로' CHECK (json_valid(`image_urls`)),
  `is_popup` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '팝업 여부',
  `is_banner` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '배너 여부',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_board_id_foreign` (`board_id`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_admin_id_foreign` (`admin_id`),
  CONSTRAINT `posts_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.rank_bonuses 구조 내보내기
CREATE TABLE IF NOT EXISTS `rank_bonuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `policy_id` bigint(20) unsigned NOT NULL,
  `transfer_id` bigint(20) unsigned NOT NULL,
  `self_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '개인매출',
  `group_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '그룹매출',
  `bonus` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수익',
  `referral_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '직추천 인원',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rank_bonuses_user_id_foreign` (`user_id`),
  KEY `rank_bonuses_policy_id_foreign` (`policy_id`),
  KEY `rank_bonuses_transfer_id_foreign` (`transfer_id`),
  CONSTRAINT `rank_bonuses_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `rank_policies` (`id`),
  CONSTRAINT `rank_bonuses_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `income_transfers` (`id`),
  CONSTRAINT `rank_bonuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.rank_bonus_referrals 구조 내보내기
CREATE TABLE IF NOT EXISTS `rank_bonus_referrals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `bonus_id` bigint(20) unsigned NOT NULL,
  `self_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '개인매출',
  `group_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '그룹매출',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rank_bonus_referrals_user_id_foreign` (`user_id`),
  KEY `rank_bonus_referrals_bonus_id_foreign` (`bonus_id`),
  CONSTRAINT `rank_bonus_referrals_bonus_id_foreign` FOREIGN KEY (`bonus_id`) REFERENCES `rank_bonuses` (`id`),
  CONSTRAINT `rank_bonus_referrals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.rank_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `rank_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` bigint(20) unsigned NOT NULL,
  `self_sales` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '개인매출 추가자격',
  `bonus` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '보너스',
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '조건' CHECK (json_valid(`conditions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rank_policies_grade_id_foreign` (`grade_id`),
  CONSTRAINT `rank_policies_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `user_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.referral_bonuses 구조 내보내기
CREATE TABLE IF NOT EXISTS `referral_bonuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `staking_id` bigint(20) unsigned NOT NULL,
  `transfer_id` bigint(20) unsigned NOT NULL,
  `referrer_id` bigint(20) unsigned NOT NULL COMMENT '산하 회원 번호',
  `bonus` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수익',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.referral_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `referral_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` bigint(20) unsigned NOT NULL,
  `level_1_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_2_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_3_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_4_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_5_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_6_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_7_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_8_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_9_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_10_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_11_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_12_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_13_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_14_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_15_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_16_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_17_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_18_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_19_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_20_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_21_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_policies_grade_id_foreign` (`grade_id`),
  CONSTRAINT `referral_policies_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `user_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.sessions 구조 내보내기
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.stakings 구조 내보내기
CREATE TABLE IF NOT EXISTS `stakings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `asset_id` bigint(20) unsigned NOT NULL,
  `income_id` bigint(20) unsigned NOT NULL,
  `staking_id` bigint(20) unsigned NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending' COMMENT '상태',
  `amount` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '참여 수량',
  `period` int(10) unsigned NOT NULL DEFAULT 7 COMMENT '기간',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '시작일',
  `ended_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '만료일',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stakings_user_id_foreign` (`user_id`),
  KEY `stakings_staking_id_foreign` (`staking_id`),
  KEY `stakings_wallet_id_foreign` (`asset_id`) USING BTREE,
  KEY `stakings_income_id_foreign` (`income_id`),
  CONSTRAINT `stakings_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`),
  CONSTRAINT `stakings_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `incomes` (`id`),
  CONSTRAINT `stakings_staking_id_foreign` FOREIGN KEY (`staking_id`) REFERENCES `staking_policies` (`id`),
  CONSTRAINT `stakings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.staking_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `staking_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coin_id` bigint(20) unsigned NOT NULL,
  `staking_type` enum('maturity','daily') NOT NULL DEFAULT 'maturity' COMMENT '지급 타입',
  `min_quantity` int(11) NOT NULL COMMENT '최소 참여수량',
  `max_quantity` int(11) NOT NULL COMMENT '최대 참여수량',
  `daily` decimal(20,9) NOT NULL COMMENT '데일리 수익률',
  `period` int(11) NOT NULL COMMENT '기간',
  `memo` varchar(255) DEFAULT NULL COMMENT '메모',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staking_policies_coin_id_foreign` (`coin_id`),
  CONSTRAINT `staking_policies_coin_id_foreign` FOREIGN KEY (`coin_id`) REFERENCES `coins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.staking_policy_translations 구조 내보내기
CREATE TABLE IF NOT EXISTS `staking_policy_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `policy_id` bigint(20) unsigned NOT NULL,
  `locale` varchar(5) NOT NULL COMMENT '상품 이름',
  `name` varchar(255) NOT NULL COMMENT '메모',
  `memo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staking_policy_translations_policy_id_foreign` (`policy_id`),
  CONSTRAINT `staking_policy_translations_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `staking_policies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.staking_refunds 구조 내보내기
CREATE TABLE IF NOT EXISTS `staking_refunds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `staking_id` bigint(20) unsigned NOT NULL,
  `transfer_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '금액',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staking_refunds_user_id_foreign` (`user_id`),
  KEY `staking_refunds_staking_id_foreign` (`staking_id`),
  KEY `staking_refunds_transfer_id_foreign` (`transfer_id`),
  CONSTRAINT `staking_refunds_staking_id_foreign` FOREIGN KEY (`staking_id`) REFERENCES `stakings` (`id`),
  CONSTRAINT `staking_refunds_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `asset_transfers` (`id`),
  CONSTRAINT `staking_refunds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.staking_rewards 구조 내보내기
CREATE TABLE IF NOT EXISTS `staking_rewards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `staking_id` bigint(20) unsigned NOT NULL,
  `transfer_id` bigint(20) unsigned NOT NULL,
  `profit` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수익',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staking_rewards_user_id_foreign` (`user_id`),
  KEY `staking_rewards_staking_id_foreign` (`staking_id`),
  KEY `staking_rewards_transfer_id_foreign` (`transfer_id`),
  CONSTRAINT `staking_rewards_staking_id_foreign` FOREIGN KEY (`staking_id`) REFERENCES `stakings` (`id`),
  CONSTRAINT `staking_rewards_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `income_transfers` (`id`),
  CONSTRAINT `staking_rewards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.subscription_bonuses 구조 내보내기
CREATE TABLE IF NOT EXISTS `subscription_bonuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `transfer_id` bigint(20) unsigned NOT NULL COMMENT '수익 내역 번호',
  `referrer_id` bigint(20) unsigned NOT NULL COMMENT '산하 회원 번호',
  `withdrawal_id` bigint(20) unsigned NOT NULL COMMENT '외부 출금 번호',
  `bonus` decimal(20,9) NOT NULL COMMENT '보너스',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bonuses_user_id_foreign` (`user_id`),
  KEY `bonuses_transfer_id_foreign` (`transfer_id`),
  CONSTRAINT `bonuses_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `income_transfers` (`id`),
  CONSTRAINT `bonuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.subscription_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `subscription_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` bigint(20) unsigned NOT NULL,
  `level_1_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_2_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_3_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_4_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_5_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_6_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_7_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_8_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_9_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_10_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_11_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_12_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_13_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_14_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_15_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_16_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_17_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_18_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_19_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_20_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `level_21_rate` decimal(20,9) NOT NULL DEFAULT 0.000000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_policies_grade_id_foreign` (`grade_id`),
  CONSTRAINT `subscription_policies_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `user_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.tradings 구조 내보내기
CREATE TABLE IF NOT EXISTS `tradings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `coin_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '잔액',
  `daily` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '데일리 수익률',
  `profit_rate` decimal(20,9) DEFAULT NULL COMMENT '수익률',
  `current_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '트레이딩 횟수',
  `max_count` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '최대 트레이딩 횟수',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tradings_user_id_foreign` (`user_id`),
  KEY `tradings_coin_id_foreign` (`coin_id`),
  CONSTRAINT `tradings_coin_id_foreign` FOREIGN KEY (`coin_id`) REFERENCES `coins` (`id`),
  CONSTRAINT `tradings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.trading_policies 구조 내보내기
CREATE TABLE IF NOT EXISTS `trading_policies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `profit_rate` decimal(20,9) NOT NULL COMMENT '수익률',
  `trading_count` int(10) unsigned NOT NULL COMMENT '트레이딩 횟수',
  `trading_days` varchar(50) DEFAULT NULL COMMENT '트레이딩 가능 요일',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.trading_profits 구조 내보내기
CREATE TABLE IF NOT EXISTS `trading_profits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL COMMENT '회원 번호',
  `trading_id` bigint(20) unsigned NOT NULL COMMENT '트레이딩 번호',
  `transfer_id` bigint(20) unsigned NOT NULL COMMENT '수익 내역 번호',
  `profit` decimal(20,9) NOT NULL DEFAULT 0.000000000 COMMENT '수익',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trading_profits_user_id_foreign` (`user_id`),
  KEY `trading_profits_trading_id_foreign` (`trading_id`),
  KEY `trading_profits_transfer_id_foreign` (`transfer_id`),
  CONSTRAINT `trading_profits_trading_id_foreign` FOREIGN KEY (`trading_id`) REFERENCES `tradings` (`id`),
  CONSTRAINT `trading_profits_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `tradings` (`id`),
  CONSTRAINT `trading_profits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.users 구조 내보내기
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '이름',
  `account` varchar(255) NOT NULL COMMENT '계정',
  `password` varchar(255) NOT NULL COMMENT '비밀번호',
  `remember_token` varchar(100) DEFAULT NULL COMMENT '토큰',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT '이메일 인증일',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_account_unique` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=1000045 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.user_grades 구조 내보내기
CREATE TABLE IF NOT EXISTS `user_grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '등급 이름',
  `level` int(10) unsigned NOT NULL COMMENT '등급 순서',
  `description` text DEFAULT NULL COMMENT '등급 설명',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_grades_level_unique` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.user_otps 구조 내보내기
CREATE TABLE IF NOT EXISTS `user_otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `last_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_otps_user_id_foreign` (`user_id`),
  CONSTRAINT `user_otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 cube_ai.user_profiles 구조 내보내기
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL COMMENT '추천 회원 번호',
  `level` tinyint(4) NOT NULL DEFAULT 1 COMMENT '회원 레벨',
  `grade_id` bigint(20) unsigned DEFAULT 1 COMMENT '회원 등급',
  `email` varchar(100) NOT NULL COMMENT '이메일',
  `phone` varchar(20) NOT NULL COMMENT '전화 번호',
  `post_code` varchar(10) DEFAULT NULL COMMENT '우편 번호',
  `address` varchar(255) DEFAULT NULL COMMENT '주소',
  `detail_address` varchar(255) DEFAULT NULL COMMENT '상세 주소',
  `meta_uid` varchar(30) DEFAULT NULL COMMENT '메타웨이브 유아이디',
  `is_valid` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '유효 계정 여부',
  `is_frozen` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '계좌 동결 여부',
  `is_kyc_verified` enum('n','y') NOT NULL DEFAULT 'n' COMMENT 'kyc 인증 여부',
  `memo` varchar(255) DEFAULT NULL COMMENT '관리자 메모',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profiles_user_id_foreign` (`user_id`),
  KEY `user_profiles_grade_id_foreign` (`grade_id`),
  CONSTRAINT `user_profiles_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `user_grades` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
