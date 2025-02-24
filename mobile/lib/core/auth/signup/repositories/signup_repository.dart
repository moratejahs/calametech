import 'package:calametech/constants/api_paths.dart';
import 'package:calametech/utils/services/rest_api_service.dart';
import 'package:calametech/utils/services/secure_storage_service.dart';
import 'package:flutter/foundation.dart';

class SignupRepository {
  final RestApiService restApiService;
  final SecureStorageService storage;

  const SignupRepository({
    required this.restApiService,
    required this.storage,
  });

  Future<Map<String, dynamic>> signup(String name, String email, String password, String confirmPassword) async {
    try {
      final response = await restApiService.post(
        ApiPaths.signup,
        {'name': name, 'email': email, 'password': password, 'password_confirmation': confirmPassword},
      );

      debugPrint('response: $response');

      if (response.containsKey('errors')) {
        return {'errors': response['errors']};
      }

      if (response.containsKey('success')) {
        return {
          'success': response['success'],
        };
      }

      throw Exception('Unexpected Format');
    } catch (e) {
      rethrow;
    }
  }
}
