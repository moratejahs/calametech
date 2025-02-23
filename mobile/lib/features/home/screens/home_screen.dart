import 'package:calametech/constants/route_constants.dart';
import 'package:calametech/core/auth/login/bloc/login_bloc.dart';
import 'package:calametech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocListener<LoginBloc, LoginState>(
      listener: (context, state) {
        if (state is LoginInitial) {
          context.go(RouteConstants.login);
        }
      },
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('Home'),
        ),
        body: Center(
          child: Column(
            children: [
              ElevatedButton(
                onPressed: () async {
                  if (!context.mounted) return;
                  context.read<LoginBloc>().add(const LogoutButtonPressed());
                },
                child: const Text('Log Out'),
              ),
              BlocBuilder<LoginBloc, LoginState>(
                builder: (context, state) {
                  if (state is LoginFailure) {
                    return Column(
                      mainAxisAlignment: MainAxisAlignment.start,
                      children: [
                        Text('Input Errors: ${state.errors}'),
                        Text('Error Message: ${state.message}'),
                      ],
                    );
                  }
                  if (state is LoginSuccess) {
                    return Text({state.user}.toString());
                  }
                  return const SizedBox();
                },
              ),
              const SizedBox(height: 20),
              BlocConsumer<ConnectivityBloc, ConnectivityState>(
                listener: (context, state) {
                  if (state is ConnectivityFailure) {
                    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                      content: Text(state.error),
                      duration: const Duration(seconds: 2),
                    ));
                  }

                  if (state is ConnectivitySuccess) {
                    ScaffoldMessenger.of(context).hideCurrentSnackBar();
                  }
                },
                builder: (context, state) {
                  if (state is ConnectivityInitial) {
                    return const CircularProgressIndicator();
                  } else if (state is ConnectivitySuccess) {
                    return Text('Connectivity: ${state.connectivityResults}');
                  } else if (state is ConnectivityFailure) {
                    return Text(state.error);
                  } else {
                    return const Center(child: CircularProgressIndicator());
                  }
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
