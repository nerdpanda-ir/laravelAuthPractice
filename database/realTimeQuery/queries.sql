/*select
       "#",
       concat(
       "<a type=\"button\" class=\"btn-floating btn-lg btn-tw\" href=\"http://laravel.local/admin/management/1", `admins`.`id` , "/edit\"><i class=\"fab fa-twitter\"></i></a>")as "action",
       concat("<img src=\"",thumbnail,"\">")  as "image",
       username,
       if(admins.active,"<i class=\"fa-solid fa-circle-check userActive\"></i>","<i class=\"fa-solid fa-ban userBan \"></i>") as "active",
       "status",
       nick,
       name,
       family,
       email,
       email_verified_at as "emailVerifyDate",
       phone,
       phone_verified_at as "phoneVerifyDate",
       admins.created_at as "createDate",
       admins.updated_at as "updateDate",
       max(`admin_access_tokens`.`created_at`) as `last_log_in`
from `admins` left join `admin_access_tokens` on `admins`.`id` = `admin_access_tokens`.`admin_id` where `admins`.`deleted_at` is null group by `admins`.`id` order by `last_log_in` desc, `admins`.`created_at` desc
*/
delete from `admins`;
select * from `admins`
where `deleted_at` is not null
order by `deleted_at` desc
