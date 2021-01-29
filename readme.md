#项目的初始化
    使用composer工具进行包的安装

        执行  composer install

    创建.env文件
        
        执行 cp .env.example .env

    修改 .env文件

        主要修改数据库配置：

            DB_CONNECTION=mysql
            
            DB_HOST=127.0.0.1
            
            DB_PORT=3306
            
            DB_DATABASE=education
            
            DB_USERNAME=homestead
            
            DB_PASSWORD=secret

    配置好数据库设置后要创建指定的数据库

    执行迁移,自动的创建表

        php artisan migrate  

    生成 laravel key

        php artisan key:generate





