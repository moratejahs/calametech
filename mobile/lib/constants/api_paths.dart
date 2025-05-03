import 'package:flutter_dotenv/flutter_dotenv.dart';

class ApiPaths {
  static const String emulatorIP = '10.0.2.2';
  static const String wifiIP = '192.168.1.7';
  static const String port = '8000';

  static const String localApiUrl = 'http://$emulatorIP:$port/api/v1';
  static const String wifiApiUrl = 'http://$wifiIP:$port/api/v1';

  static const String prodRootUrl = 'https://calamitech.site';
  static const String prodBaseApiUrl = 'https://calamitech.site/api/v1';

  static String rootUrl = dotenv.env['APP_ENV'] == 'local' ? 'http://$wifiIP:$port' : prodRootUrl;
  static String baseUrl = dotenv.env['APP_ENV'] == 'local' ? wifiApiUrl : prodBaseApiUrl;

  static String storage = '$rootUrl/storage/';

  static String login = '$baseUrl/login';
  static String register = '$baseUrl/register';
  static String logout = '$baseUrl/logout';
  static String getUser = '$baseUrl/user';
  static String news = '$baseUrl/news';
  static String report = '$baseUrl/report';

  static const String aiApiUrl = 'https://api.openai.com/v1/chat/completions';
}
