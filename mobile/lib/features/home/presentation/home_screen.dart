import 'dart:convert';

import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/core/shared_widgets/app_bottom_nav.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/news/presentation/news_cards.dart';
import 'package:calamitech/features/report/presentation/report_form.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:calamitech/features/location/cubit/location_cubit.dart';
import 'package:calamitech/features/tips/presentation/calamity_tips.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late PusherChannelsFlutter pusher;
  late AuthUserService authUserService;

  @override
  void initState() {
    super.initState();
    _startLocationUpdates();
    _initPusher();
  }

  void _startLocationUpdates() {
    if (!mounted) return;
    context.read<LocationCubit>().startLocationUpdates();
  }

  Future<void> _initPusher() async {
    authUserService = context.read<AuthUserService>();
    pusher = PusherChannelsFlutter();

    try {
      await pusher.init(
        apiKey: dotenv.env['PUSHER_APP_KEY'] ?? '',
        cluster: dotenv.env['PUSHER_APP_CLUSTER'] ?? '',
        onConnectionStateChange: (currentState, previousState) {
          debugPrint("Pusher state changed: $previousState → $currentState");
        },
        onError: (message, code, error) {
          debugPrint("Pusher error: $message (Code: $code)");
        },
        onSubscriptionSucceeded: (channelName, data) {
          debugPrint("Successfully subscribed to: $channelName");
        },
      );

      // Connect to Pusher
      await pusher.connect();
      debugPrint("Connected to Pusher: ${pusher.connectionState}");

      // Listen for events
      pusher.onEvent = _handlePusherEvent;

      final user = await authUserService.get();
      if (user != null) {
        await pusher.subscribe(channelName: 'user.${user.id}');
        debugPrint("Subscribed to channel: user.${user.id}");
      } else {
        debugPrint("No authenticated user found.");
      }
    } catch (e, stackTrace) {
      debugPrint("Exception initializing Pusher: $e");
      debugPrint("StackTrace: $stackTrace");
    }
  }

  void _handlePusherEvent(PusherEvent event) {
    debugPrint("Received Event: ${event.channelName} | ${event.eventName}");
    debugPrint("Event Data: ${event.data}");

    if (event.eventName == 'sos.resolved') {
      try {
        final data = jsonDecode(event.data ?? '{}');

        if (!mounted) return;

        // Safely schedule snackbar display
        WidgetsBinding.instance.addPostFrameCallback((_) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(
                '🚨 Your ${data['type']} SOS has been resolved!',
                style: const TextStyle(fontSize: 16),
              ),
              duration: const Duration(seconds: 5),
              behavior: SnackBarBehavior.floating,
              backgroundColor: Colors.green,
            ),
          );
        });

        debugPrint("Decoded SOS Resolved Data: $data");
      } catch (e) {
        debugPrint("Error parsing SOS resolved data: $e");
      }
    }
  }

  @override
  void dispose() {
    super.dispose();
    pusher.disconnect();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Calamitech',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.w700,
          ),
        ),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: const SafeArea(
        child: Padding(
          padding: EdgeInsets.all(8.0),
          child: SingleChildScrollView(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                NewsCards(),
                CalamityTips(),
                ReportForm(),
              ],
            ),
          ),
        ),
      ),
      bottomNavigationBar: appBottomNav(context, AppRoutes.home),
    );
  }
}
