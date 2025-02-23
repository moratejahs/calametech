import 'package:calametech/config/router/app_router.dart';
import 'package:calametech/constants/api_paths.dart';
import 'package:calametech/core/auth/login/bloc/login_bloc.dart';
import 'package:calametech/core/auth/login/repositories/login_repository.dart';
import 'package:calametech/core/auth/signup/bloc/signup_bloc.dart';
import 'package:calametech/core/auth/signup/repositories/signup_repository.dart';
import 'package:calametech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:calametech/utils/services/rest_api_service.dart';
import 'package:calametech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final RestApiService restApiService = RestApiService(baseUrl: ApiPaths.localApiUrl);
    final SecureStorageService storage = SecureStorageService();

    return MultiRepositoryProvider(
      providers: [
        RepositoryProvider<LoginRepository>(
          create: (_) => LoginRepository(restApiService: restApiService, storage: storage),
        ),
        RepositoryProvider<SignupRepository>(
          create: (_) => SignupRepository(restApiService: restApiService, storage: storage),
        ),
      ],
      child: MultiBlocProvider(
        providers: [
          BlocProvider<ConnectivityBloc>(
            create: (_) => ConnectivityBloc(),
          ),
          BlocProvider<LoginBloc>(
            create: (context) => LoginBloc(
              loginRepository: context.read<LoginRepository>(),
              storage: storage,
            ),
          ),
          BlocProvider<SignupBloc>(
              create: (context) => SignupBloc(
                    signupRepository: context.read<SignupRepository>(),
                    storage: storage,
                  )),
        ],
        child: MaterialApp.router(
          theme: ThemeData(
            colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
            useMaterial3: true,
          ),
          routerConfig: AppRouter().router,
        ),
      ),
    );
  }
}
