import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:calamitech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class ReportScreen extends StatelessWidget {
  const ReportScreen({super.key});

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
          title: const Text('Report'),
        ),
        body: const Center(
          child: Text('Report Screen'),
        ),
      ),
    );
  }
}
