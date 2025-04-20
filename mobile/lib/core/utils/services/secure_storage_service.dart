import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class SecureStorageService {
  final storage = const FlutterSecureStorage();

  Future<bool> writeValue(String key, String value) async {
    await storage.write(key: key, value: value);
    return true;
  }

  Future<dynamic> readValue(String key) async {
    return await storage.read(key: key);
  }

  Future<bool> deleteValue(String key) async {
    await storage.delete(key: key);
    return true;
  }

  Future<bool> deleteAll(String key) async {
    await storage.deleteAll();
    return true;
  }

  Future<void> writeAuthToken(String token) async {
    await storage.write(key: 'auth_token', value: token);
  }

  Future<String> readAuthToken() async {
    final String? authToken = await storage.read(key: 'auth_token');

    if (authToken == null) {
      throw Exception('No auth token found');
    }

    return authToken;
  }

  Future<void> deleteAuthToken() async {
    await storage.delete(key: 'auth_token');
  }
}
