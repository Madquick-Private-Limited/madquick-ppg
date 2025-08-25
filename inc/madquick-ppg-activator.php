<?php
defined('ABSPATH') || exit;

/** save defualt setting of the plugin on activation */

class Madquick_PPG_Activator {
    
    /**
     * Run on plugin activation
     */
    public static function activate() {
        $browser_keywords = [
            "update my browser", "upgrade my browser", "browser update", "browser upgrade",
            "update browser", "upgrade browser", "get browser update", "download browser update",
            "install browser update", "update web browser", "upgrade web browser", "refresh my browser",
            "get latest browser", "download latest browser", "install latest browser", "update my web browser",
            "upgrade my web browser", "update to latest browser", "get newest browser version",
            "latest browser update", "latest browser version download", "update browser version",
            "upgrade browser version", "download browser upgrade", "update my internet browser",
            "upgrade my internet browser", "internet browser update", "internet browser upgrade",
            "install browser upgrade", "browser version update", "browser version upgrade",
            "get browser upgrade", "update to new browser", "upgrade to new browser",
            "download new browser", "install new browser", "newest browser update",
            "update your browser", "get the latest browser", "upgrade browser now",
            "modern browser update", "update old browser", "fix browser issues",
            "browser compatibility fix", "solve browser error", "speed up browser",
            "improve browser performance", "secure your browser", "latest browser version",
            "outdated browser warning", "make browser faster", "fast browser solution",
            "unsupported browser fix", "web browser update", "chrome browser not working",
            "browser update required", "fix old browser", "update now for speed",
            "update for better security", "web compatibility issue", "website not working?",
            "broken website display", "browser is too old", "refresh browser version",
            "update browser to continue", "performance browser fix", "enable full site features",
            "website loading fix", "outdated software detected", "switch to modern browser",
            "browser error message", "secure browsing tool", "browser speed upgrade",
            "web page display issues", "improve internet browsing", "fix broken web layout",
            "outdated chrome warning", "better web experience", "javascript not supported",
            "update to support JS", "HTML5 not supported", "modern site compatibility",
            "web app not supported", "outdated device browser", "update for mobile browsing",
            "enable responsive features", "site is broken?", "full web compatibility",
            "display bug in browser", "improve browser UI", "update required to view",
            "improve browsing stability", "update browser plugins", "fix javascript issues",
            "web tools not working?", "update browser compatibility", "page not loading correctly",
            "see full content", "unlock full site features", "better viewing experience",
            "fix broken fonts", "stop browser crashes", "outdated browser detected",
            "fix browser freeze", "browser patch required", "browser upgrade needed",
            "your browser is insecure", "web browser patch", "get stable browser",
            "update for rich features", "ensure full functionality", "enable latest web tech",
            "update for faster load", "browser enhancement guide", "site not mobile friendly?",
            "enable new features", "get new browser update", "update to latest version",
            "upgrade to latest version", "download browser version", "install browser version",
            "update browser software", "upgrade browser software", "get browser software update",
            "download browser software update", "install browser software update",
            "update current browser", "upgrade current browser", "new browser update",
            "latest browser download", "upgrade to newest browser", "install newest browser",
            "download latest browser update", "update for browser", "upgrade for browser",
            "fix browser update", "repair browser update", "browser patch update",
            "upgrade old browser", "update my browsing software", "upgrade my browsing software",
            "download new browser version", "install latest browser version", "get browser version update",
            "update outdated browser", "upgrade outdated browser", "refresh internet browser",
            "refresh web browser", "download browser latest", "install browser latest",
            "get latest browser version", "update to browser latest", "upgrade to browser latest"
        ];

        $strong_password_generator_keywords = [
            "strong password generator",
            "secure password generator",
            "random password generator",
            "password generator online",
            "create strong password",
            "best password generator",
            "pronounceable password generator",
            "high entropy password generator",
            "password generator with symbols",
            "password generator without special characters",
            "16 character password generator",
            "diceware passphrase generator",
            "bulk password generator",
            "password generator API",
            "NIST compliant password generator",
            "private offline password generator",
            "copy to clipboard password generator",
            "password generator with strength meter"
        ];

        // Pick a random keyword
        $random_keyword_update_my_browser_keyword = $browser_keywords[array_rand($browser_keywords)];
        $random_strong_password_generator_keyword = $strong_password_generator_keywords[array_rand($strong_password_generator_keywords)];

        $default_settings = [
            'enable_checker' => true,
            'enable_cookie_banner' => true,
            'enable_update_banner' => true,
            'update_my_browser_keyword'  => $random_keyword_update_my_browser_keyword,
            'strong_password_generator_keyword'  => $random_strong_password_generator_keyword,
        ];

        $existing = get_option('madquick_ppg_settings');

        if (!is_array($existing)) {
            add_option('madquick_ppg_settings', $default_settings);
        } else {
            // Merge missing defaults into existing
            $merged = wp_parse_args($existing, $default_settings);
            update_option('madquick_ppg_settings', $merged);
        }
    }

    /**
     * Run on plugin deactivation
     */
    public static function deactivate() {
        delete_option('madquick_ppg_settings');
    }
}
