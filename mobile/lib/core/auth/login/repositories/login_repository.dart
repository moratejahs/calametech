import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/auth/login/models/user.dart';
import 'package:calamitech/utils/services/rest_api_service.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';

class LoginRepository {
  final RestApiService restApiService;
  final SecureStorageService storage;

  const LoginRepository({
    required this.restApiService,
    required this.storage,
  });

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await restApiService.post(
        ApiPaths.login,
        {'email': email, 'password': password},
      );

      if (response.containsKey('errors')) {
        return {'errors': response['errors']};
      }

      if (response.containsKey('user') && response.containsKey('token')) {
        return {
          'user': User.fromMap({
            'id': response['user']['id'],
            'name': response['user']['name'],
            'email': response['user']['email'],
            'token': response['token'],
          })
        };
      }

      throw Exception('Unexpected Format');
    } catch (e) {
      rethrow;
    }
  }

  Future<bool> logout(String token) async {
    final response = await restApiService.post(
      ApiPaths.logout,
      null,
      token: token,
    );

    return response.containsKey('success');
  }
}
