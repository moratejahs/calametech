import 'package:calamitech/config/router/app_router.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/app/cubit/navigation_cubit.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:calamitech/core/auth/login/repositories/login_repository.dart';
import 'package:calamitech/core/auth/signup/bloc/signup_bloc.dart';
import 'package:calamitech/core/auth/signup/repositories/signup_repository.dart';
import 'package:calamitech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:calamitech/features/sos/bloc/sos_bloc.dart';
import 'package:calamitech/features/sos/repositories/sos_repository.dart';
import 'package:calamitech/utils/services/rest_api_service.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import 'core/location/cubit/location_cubit.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final RestApiService restApiService =
        RestApiService(baseUrl: ApiPaths.wifiApiUrl);
    final SecureStorageService storage = SecureStorageService();

    return MultiRepositoryProvider(
      providers: [
        RepositoryProvider<LoginRepository>(
          create: (_) =>
              LoginRepository(restApiService: restApiService, storage: storage),
        ),
        RepositoryProvider<SignupRepository>(
          create: (_) => SignupRepository(
              restApiService: restApiService, storage: storage),
        ),
        RepositoryProvider<SOSRepository>(
          create: (_) =>
              SOSRepository(restApiService: restApiService),
        ),
      ],
      child: MultiBlocProvider(
        providers: [
          BlocProvider<ConnectivityBloc>(
            create: (_) => ConnectivityBloc(),
          ),
          BlocProvider<LocationCubit>(
            create: (_) => LocationCubit(),
          ),
          BlocProvider<NavigationCubit>(
            create: (_) => NavigationCubit(),
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
          BlocProvider<SosBloc>(
              create: (context) => SosBloc(
                    sosRepository: context.read<SOSRepository>(),
                    storage: storage,
                  )),
        ],
        child: MaterialApp.router(
          theme: AppTheme.lightTheme,
          routerConfig: AppRouter().router,
        ),
      ),
    );
  }
}
