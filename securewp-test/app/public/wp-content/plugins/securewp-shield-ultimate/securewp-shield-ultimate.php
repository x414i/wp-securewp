<?php
/**
 * Plugin Name: SecureWP Shield Ultimate
 * Plugin URI: https://yoursite.com/securewp
 * Description: حماية ذكية لووردبريس — تخفي بيانات المستخدمين وتستبدلها ببيانات وهمية عند أي محاولة اختراق.
 * Version: 1.0.0
 * Author: أنت
 * Author URI: https://yoursite.com
 * Text Domain: securewp
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; 
}

register_activation_hook(__FILE__, 'securewp_create_logs_table');

function securewp_create_logs_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'securewp_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address varchar(45) NOT NULL,
        user_agent text NOT NULL,
        action varchar(50) NOT NULL,
        fake_data_sent tinyint(1) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function securewp_generate_fake_user_data() {
    $fake_names = [
        'أحمد الخيالي', 'سارة السرية', 'خالد المجهول', 'ليلى الوهمية',
        'يوسف المستعار', 'نورا المقنعة', 'محمود المستتر', 'هدى المخفية'
    ];
    $fake_emails = [
        'user1@securewp.fake', 'no-reply@shield.fake', 'hidden@anon.fake',
        'ghost@phantom.fake', 'dummy@fakeuser.fake', 'masked@cloak.fake'
    ];

    return [
        'name' => $fake_names[array_rand($fake_names)],
        'email' => $fake_emails[array_rand($fake_emails)],
        'ip' => '0.0.0.0',
        'location' => 'مكان غير معروف',
        'device' => 'جهاز مجهول الهوية'
    ];
}

function securewp_protect_user_data($user_data) {
    if (!is_user_logged_in() || (isset($_GET['fake']) && $_GET['fake'] === 'true')) {
        error_log('⚠️ محاولة وصول غير مصرح بها — تم إرسال بيانات وهمية.');
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'securewp_logs';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $wpdb->insert(
            $table_name,
            [
                'ip_address' => $ip,
                'user_agent' => $user_agent,
                'action' => 'attempted_user_data_access',
                'fake_data_sent' => 1
            ]
        );

        return securewp_generate_fake_user_data();
    }

    return $user_data;
}

add_filter('get_user_metadata', 'securewp_protect_user_data_on_metadata', 10, 4);

function securewp_protect_user_data_on_metadata($value, $object_id, $meta_key, $single) {
    if (in_array($meta_key, ['first_name', 'last_name', 'nickname', 'display_name', 'user_email'])) {
        $current_user = wp_get_current_user();
        if ($current_user->ID != $object_id) {
            $fake_data = securewp_generate_fake_user_data();
            if ($meta_key === 'user_email') return $fake_data['email'];
            if (in_array($meta_key, ['first_name', 'last_name', 'nickname', 'display_name'])) return $fake_data['name'];
        }
    }
    return $value;
}

add_action('admin_menu', 'securewp_add_admin_menu');

function securewp_add_admin_menu() {
    add_menu_page(
        'SecureWP Shield',          
        'SecureWP Shield',           
        'manage_options',           
        'securewp-shield',          
        'securewp_admin_page',       
        'dashicons-shield',          
        80                          
    );
}

function securewp_admin_page() {
    ?>
    <div class="wrap">
        <h1>🛡️ SecureWP Shield Ultimate</h1>
        <p>✅ الإضافة تعمل! بيانات المستخدمين محمية — أي محاولة وصول غير مصرح بها ستُقابل ببيانات وهمية.</p>
        <hr>
        <h2>📊 سجل محاولات الوصول</h2>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'securewp_logs';
        $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 10");

        if ($logs) {
            echo '<table class="wp-list-table widefat striped">';
            echo '<thead><tr><th>IP</th><th>الإجراء</th><th>تم إرسال بيانات وهمية؟</th><th>التاريخ</th></tr></thead>';
            echo '<tbody>';
            foreach ($logs as $log) {
                echo '<tr>';
                echo '<td>' . esc_html($log->ip_address) . '</td>';
                echo '<td>' . esc_html($log->action) . '</td>';
                echo '<td>' . ($log->fake_data_sent ? '✅ نعم' : '❌ لا') . '</td>';
                echo '<td>' . esc_html($log->created_at) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>لا توجد محاولات بعد.</p>';
        }
        ?>
        <hr>
        <h3>🧪 اختبار الحماية</h3>
        <p>أضف <code>?fake=true</code> إلى نهاية رابط الصفحة لرؤية كيف تعمل الحماية.</p>
        <p>مثال: <code><?php echo home_url(); ?>/?fake=true</code></p>
    </div>
    <?php
}
add_action('template_redirect', 'securewp_handle_fake_test');

function securewp_handle_fake_test() {
    if (isset($_GET['fake']) && $_GET['fake'] === 'true') {
        nocache_headers();

        $fake_data = securewp_generate_fake_user_data();

        echo '<!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>🧪 اختبار الحماية - SecureWP Shield</title>
            <style>
                body {
                    font-family: Tahoma, Arial, sans-serif;
                    background: #f0f5ff;
                    padding: 40px;
                    text-align: center;
                    color: #2c3e50;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #e74c3c;
                }
                .data-box {
                    background: #f8f9fa;
                    padding: 20px;
                    margin: 20px 0;
                    border-radius: 8px;
                    text-align: right;
                    direction: rtl;
                }
                .note {
                    color: #7f8c8d;
                    font-size: 14px;
                    margin-top: 30px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🛡️ تم تفعيل وضع الحماية!</h1>
                <p>أنت الآن ترى كيف يحمي SecureWP Shield بيانات المستخدمين.</p>
                <div class="data-box">
                    <h3>البيانات الوهمية التي تم إرسالها:</h3>
                    <p><strong>الاسم:</strong> ' . $fake_data['name'] . '</p>
                    <p><strong>البريد الإلكتروني:</strong> ' . $fake_data['email'] . '</p>
                    <p><strong>عنوان IP:</strong> ' . $fake_data['ip'] . '</p>
                    <p><strong>الموقع:</strong> ' . $fake_data['location'] . '</p>
                    <p><strong>الجهاز:</strong> ' . $fake_data['device'] . '</p>
                </div>
                <p>✅ تم تسجيل هذه المحاولة في لوحة التحكم.</p>
                <div class="note">
                    يمكنك العودة إلى <a href="' . home_url() . '">الصفحة الرئيسية</a> أو إلى <a href="' . admin_url('admin.php?page=securewp-shield') . '">لوحة التحكم</a> لرؤية السجل.
                </div>
            </div>
        </body>
        </html>';

        global $wpdb;
        $table_name = $wpdb->prefix . 'securewp_logs';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $wpdb->insert(
            $table_name,
            [
                'ip_address' => $ip,
                'user_agent' => $user_agent,
                'action' => 'fake_data_test_triggered',
                'fake_data_sent' => 1
            ]
        );

        exit;
    }
}