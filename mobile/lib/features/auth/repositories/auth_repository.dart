import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/exceptions/validation_exception.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/auth/models/user_model.dart';
import 'package:calamitech/features/auth/repositories/i_auth_repository.dart';
import 'package:http/http.dart' as http;

class AuthRepository implements IAuthRepository {
  final http.Client httpClient;
  final AuthUserService authUserService;

  const AuthRepository({
    required this.httpClient,
    required this.authUserService,
  });

  @override
  Future<UserModel> login(String email, String password) async {
    final response = await httpClient.post(
      Uri.parse(ApiPaths.login),
      headers: {
        'Accept': 'application/json',
      },
      body: {
        'email': email,
        'password': password,
      },
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode == 200) {
      return UserModel.fromMap({
        'id': jsonBody['user']['id'],
        'name': jsonBody['user']['name'],
        'email': jsonBody['user']['email'],
        'token': jsonBody['token'],
      });
    } else if (response.statusCode == 422) {
      throw ValidationException(jsonBody['errors']);
    } else {
      throw Exception(jsonBody['message']);
    }
  }

  @override
  Future<UserModel> register({required String name, required String email, required String password, required String passwordConfirmation}) {
    // TODO: implement register
    throw UnimplementedError();
  }

  @override
  Future<void> logout() async {
    final user = await authUserService.get();

    if (user == null) {
      throw Exception('Unauthenticated.');
    }

    final response = await httpClient.post(
      Uri.parse(ApiPaths.logout),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ${user.token}'
      },
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode != 200) {
      throw Exception(jsonBody['message']);
    }
  }
}
