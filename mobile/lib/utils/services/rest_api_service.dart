import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;

abstract class BaseRestApiService {
  Future<dynamic> get(String url);
  Future<dynamic> post(String url, dynamic data);
}

class RestApiService implements BaseRestApiService {
  final String baseUrl;

  RestApiService({required this.baseUrl});

  @override
  Future get(String url) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl$url'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      return handleResponse(response);
    } on SocketException {
      throw Exception('No Internet Connection');
    } on TimeoutException {
      throw Exception('Connection Timeout');
    }
  }

  @override
  Future<dynamic> post(String url, dynamic data, {String? token}) async {
    try {
      final response = await http
          .post(
            Uri.parse('$baseUrl$url'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              if (token != null) 'Authorization': 'Bearer $token',
            },
            body: json.encode(data),
          )
          .timeout(const Duration(seconds: 10));

      return handleResponse(response);
    } on SocketException {
      throw Exception('No Internet Connection');
    } on TimeoutException {
      throw Exception('Connection Timeout');
    } catch (_) {
      rethrow;
    }
  }

  dynamic handleResponse(http.Response response) {
    if (response.statusCode >= 500) {
      throw Exception('Server Error ${response.statusCode}: ${json.decode(response.body)['message']}');
    }

    if (response.statusCode == 401) {
      throw Exception('Unauthorized Action');
    }

    return json.decode(response.body);
  }
}
